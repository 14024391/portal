<template>
    <div class="autocomplete">
        <input type="text" class="form-control "
               @input="onChange"
               v-model="search"
               @keyup.down.prevent="onArrowDown"
               @keyup.up="onArrowUp"
               @keydown.tab="onTab"
               @keydown.enter.prevent="onEnter"
               @click="onClick"
               :required="required"/>
        <span class="autocomplete-dropdown-icon">
            <svg version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' width='18' height='18' viewBox='0 0 24 24'><path fill='grey' d='M7.406 7.828l4.594 4.594 4.594-4.594 1.406 1.406-6 6-6-6z'></path></svg>
        </span>
        <ul id="autocomplete-results" v-show="isOpen" class="autocomplete-results">
            <li class="loading" v-if="isLoading">
                Loading results...
            </li>
            <li v-else v-for="(result, i) in results" :key="i"
                @click="setResult(result)"
                class="autocomplete-result" :class="{ 'is-active': i === arrowCounter }">
                {{ result[loopVal] }}
            </li>
        </ul>
    </div>
</template>
<script>
    export default {
        props: {
            items: {
                type: Array,
                required: false,
                default: () => []
            },
            isAsync: {
                type: Boolean,
                required: false,
                default: false
            },
            loopKey: {
                type: String,
                required: false
            },
            loopVal: {
                type: String,
                required: false
            },
            required: {
                type: Boolean,
                required: false
            },
            initialKey: {
                type: String,
            },
            initialVal: null,
            select: {
                type: Boolean,
                required: false,
                default: false
            },
        },
        data: function () {
            return {
                isOpen: false,
                results: [],
                isLoading: false,
                arrowCounter: 0,
                search: '',
                selected: ''
            }
        },
        watch: {
            items: function(val, oldValue) {
                // actually compare them
                if(val != undefined){
                    if (val.length !== oldValue.length) {
                        this.results = val;
                        this.isLoading = false;
                        let item = this.items.find(r => r[this.initialKey] == this.initialVal)
                        this.selected = item;
                        this.search = item ? item[this.loopVal]: "";
                    }
                }
            },
            initialVal: function (val) {
                let item = this.items.find(r => r[this.initialKey] == this.initialVal)
                this.selected = item;
                if(!this.search){
                    this.search = item ? item[this.loopVal]: "";
                }else{
                    this.search = item ? item[this.loopVal]: this.search;
                }

            }
        },
        mounted() {
            let item = this.items.find(r => r[this.initialKey] == this.initialVal)
            this.selected = item;
            this.search = item ? item[this.loopVal]: "";
            document.addEventListener("click", this.handleClickOutside);
        },
        destroyed() {
            document.removeEventListener("click", this.handleClickOutside);
        },
        methods: {
            onClick: function () {
                this.filterResults();
                this.isOpen = true;
            },
            onChange: function () {
                this.filterResults();
                this.isOpen = true;
                this.selected = null;
                this.updateParent();
            },

            filterResults: function() {
                // first uncapitalize all the things
                this.results = this.items.filter(item => {
                    return item[this.loopVal].toLowerCase().indexOf(this.search.toLowerCase()) > -1;
                });
            },
            setResult: function(result) {
                this.search = result[this.loopVal];
                this.selected = result;
                this.isOpen = false;
                this.updateParent();
            },
            onArrowDown: function(evt) {
                if (this.arrowCounter < this.results.length) {
                    this.arrowCounter = this.arrowCounter + 1;
                }
            },
            onArrowUp: function() {
                if (this.arrowCounter > 0) {
                    this.arrowCounter = this.arrowCounter - 1;
                }
            },
            onEnter: function(event){
                if(!this.select){
                    this.selected = this.arrowCounter == -1 ? this.search : this.results[this.arrowCounter];
                    if(this.selected){
                        this.search = this.selected == this.search ? this.search : this.selected[this.loopVal];
                    }
                }else{
                    this.selected = this.arrowCounter == -1 ? null : this.results[this.arrowCounter];
                    this.search = this.selected ? this.selected[this.loopVal] : '' ;
                }
                this.isOpen = false;
                this.arrowCounter = -1;
                this.updateParent();
            },
            onTab: function () {
                if(this.select){
                    this.search = this.selected ? this.selected[this.loopVal] : '';
                    this.updateParent();
                }
                this.isOpen = false;
                this.arrowCounter = -1;

            },
            handleClickOutside: function(evt) {
                if (!this.$el.contains(evt.target)) {
                    this.isOpen = false;
                    this.arrowCounter = -1;
                }
            },
            updateParent: function () {
                if(this.results.length == 0){
                    this.$emit("update:input", this.search);
                }else{
                    this.$emit("update:input", this.selected ? this.selected[this.initialKey]: this.search);
                }

            }
        }
    }
</script>