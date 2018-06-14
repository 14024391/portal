<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */

    public function index(Request $request)
    {
        $this->authorize('view', User::class);

        $name = $request->query('name');
        $email = $request->query('email');
        $sort_by = $request->query('sort_by') ? $request->query('sort_by') : 'id';
        $order_by = $request->query('order_by') ? $request->query('order_by') : 'dsc';

        $users = User::when($name, function ($query) use ($name){
                        return $query->where('name','like',"$name%");
                })
                ->when($email, function ($query) use ($email){
                    return $query->where('email','like',"$email%");
                })
                ->when($sort_by, function ($query) use ($sort_by,$order_by){
                    return $query->orderBy($sort_by,$order_by);
                })
                ->paginate(10);
        Session::put('url.referrer',$request->fullUrl());

        return view('admin.user.index',['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', User::class);
        $roles = Role::all();

        return view('admin.user.create',['roles' => $roles]);
    }

    public function register(Request $request)
    {
        $this->authorize('create', User::class);
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6|max:14',
            'role' => 'required',
            'active'  => 'required'
        ]);

        //Validate the incoming request using the already included validator method

        // Initialise the 2FA class
        $google2fa = app('pragmarx.google2fa');

        // Save the registration data in an array
        $registration_data = $request->all();

        // Add the secret key to the registration data
        $registration_data["google2fa_secret"] = $google2fa->generateSecretKey();

        // Save the registration data to the user session for just the next request
        $request->session()->flash('registration_data', $registration_data);

        // Generate the QR image. This is the image the user will scan with their app
        // to set up two factor authentication
        $QR_Image = $google2fa->getQRCodeInline(
            config('app.name'),
            $registration_data['email'],
            $registration_data['google2fa_secret']
        );

        return view('admin.google2fa.register', ['QR_Image' => $QR_Image, 'secret' => $registration_data['google2fa_secret'], 'reauthenticating' => false]);
    }


    public function completeRegistration(Request $request)
    {
        // add the session data back to the request input
        $request->merge(session('registration_data'));

        // Call the default laravel authentication
        return $this->store($request);
    }

    public function completeUpdate(Request $request)
    {
        // add the session data back to the request input
        $request->merge(session('registration_data'));

        // Call the default laravel authentication
        return $this->update($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $request->validate([
           'name' => 'required',
           'email' => 'required|email|unique:users',
           'password' => 'required|confirmed|min:6|max:14',
           'role' => 'required',
           'active'  => 'required',
           'google2fa_secret' => 'required',
        ]);
        try{
            $user = new User();
            if ($request->file('profile_image')){
                $avatar = $request->file('profile_image');
                $filename = time() . '.' . $avatar->getClientOriginalExtension();
                Image::make($avatar)->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(storage_path('app/public/profiles/'.$filename));
                $user->profile_url = 'profiles/'.$filename;
            }
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->active = $request->active ? 1 : 0;

            $settings = DB::table('settings')->pluck('value','name');

            $user->locale = 'en';
            $user->timezone = $settings['timezone'];
            $user->google2fa_secret = $request->get('google2fa_secret');

            $user->save();

            $user->roles()->attach($request->role);

            return redirect()->route('admin.users')->with('status', 'Profile created!');
        }catch (\Exception $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($id)
    {
        if($id != Auth::user()->id){
            $this->authorize('view', User::class);
        }

        $user = User::find($id);
        $roles = Role::all();
        $formAction = $user->google2fa_secret != null ? route('admin.user.update') : route('admin.user.register');
        return view('admin.user.show',['roles' => $roles,'user' => $user,'formAction' => $formAction]);
    }



    /**
     * Display the User Profile.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function showProfile()
    {

        $user = Auth::user();
        return view('admin.user.profile',['user' => $user]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request)
    {
        if($request->id != Auth::user()->id) {
            $this->authorize('update', User::class);
        }
        $user = User::find($request->id);

        $request->validate([
            'id' => 'required',
            'name' => 'required',
            'email' => ['required','email',Rule::unique('users')->ignore($user->id, 'id')],
            'password' => 'sometimes|confirmed',
            'role' => 'required|numeric|max:15',
            'active'  => 'sometimes|numeric'
        ]);

        try{
            $user->name = $request->name;
            $user->email = $request->email;
            if($request->password != null){
                $user->password = bcrypt($request->password);
            }

            $old_photo = $user->profile_url;
            if ($request->file('profile_image')){
                $avatar = $request->file('profile_image');
                $filename = time() . '.' . $avatar->getClientOriginalExtension();
                Image::make($avatar)->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(storage_path('app/public/profiles/'.$filename));
                $user->profile_url = 'profiles/'.$filename;
                if($old_photo != '/profiles/profile_default.jpg'){
                    Storage::disk('public')->delete($old_photo);
                }

            }


            $user->active = $request->active ? 1 : 0;
            $user->locale = $request->get('locale');
            $user->timezone = $request->get('timezone');
            $user->google2fa_secret = $request->get('google2fa_secret');

            $user->save();
            $user->roles()->detach();
            $user->roles()->attach($request->role);

            Session::put('locale',$request->get('locale'));
            Session::put('timezone',$request->get('timezone'));
            $langFile = Session::get('locale') ? Session::get('locale') : app()->getLocale();
            Session::put('translation', File::get(resource_path('lang/'.$langFile.".json")));

            return redirect()->route('admin.user',$user->id)->withInput()->with('status', 'Profile updated!');
        }catch (\Exception $e){
            return redirect()->route('admin.user',$user->id)->withErrors($e->getMessage());
        }
    }

    public function reauthenticate(Request $request,$id)
    {
        try{
            // get the logged in user
            $user = User::findOrFail($id);

            // initialise the 2FA class
            $google2fa = app('pragmarx.google2fa');

            // generate a new secret key for the user
            $user->google2fa_secret = $google2fa->generateSecretKey();

            // save the user
            $user->save();

            // generate the QR image
            $QR_Image = $google2fa->getQRCodeInline(
                config('app.name'),
                $user->email,
                $user->google2fa_secret
            );

            // Pass the QR barcode image to our view.
            return view('admin.google2fa.register', ['QR_Image' => $QR_Image,
                'secret' => $user->google2fa_secret,
                'reauthenticating' => true
            ]);
        }catch (ModelNotFoundException $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        try{
            $this->authorize('delete', User::class);
            $user = User::find($request->id);
            $user->roles()->detach();
            User::destroy($user->id);
            return redirect()->route('users')->with('status', 'Successfully deleted the User');
        }catch (\Exception $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
