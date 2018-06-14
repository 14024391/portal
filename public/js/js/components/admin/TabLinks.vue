<template>
    <div id="v-pills-tab" class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
        <a class="nav-link active" id="vechile-details-tab" data-toggle="pill" href="#vechile-details" role="tab" aria-controls="vechile-details" aria-selected="true">Vehicle Details</a>
        <a class="nav-link" id="vehicle-specification-tab" data-toggle="pill" href="#vehicle-specification" role="tab" aria-controls="vehicle-specification" aria-selected="false">Vehicle Specification</a>
        <a class="nav-link" id="images-&-video-tab" data-toggle="pill" href="#images-&-video" role="tab" aria-controls="images-&-video" aria-selected="false">Images & Video</a>
        <a class="nav-link" id="condition-&-history-tab" data-toggle="pill" href="#condition-&-history" role="tab" aria-controls="condition-&-history" aria-selected="false">Condition & History</a>
        <a class="nav-link" id="description-&-features-tab" data-toggle="pill" href="#description-&-features" role="tab" aria-controls="description-&-features" aria-selected="false">Description & Features</a>
        <a class="nav-link" id="vehicle-visibility-tab" data-toggle="pill" href="#vehicle-visibility" role="tab" aria-controls="vehicle-visibility-tab" aria-selected="false">Visibility</a>
    </div>
</template>

<script>
    export default {
        name: "tab-links"
    }
</script>

<style scoped>

</style>