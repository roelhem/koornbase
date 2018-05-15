<template>

    <div class="btn-group">

        <button class="btn btn-link" :class="{'disabled': prev === null}" @click="changeTo(prev)">
            Vorige
        </button>

        <button class="btn btn-link" v-for="page in pages" :key="page.key" :class="{'disabled': page.active}" @click="changeTo(page.number)">
            {{ page.number }}
        </button>

        <button class="btn btn-link" :class="{'disabled': next === null}" @click="changeTo(next)">
            Volgende
        </button>

    </div>

</template>

<script>
    export default {
        name: "search-pager",
        props: {
            meta:Object
        },
        computed: {
            pages: function() {
                // prepare the result
                let res = [];

                // check the meta data
                let meta = this.meta;
                if(meta.current_page && meta.last_page) {

                    //
                    for(let i = 1; i <= meta.last_page; i++) {
                        res.push({
                            number: i,
                            key: 'page-'+i,
                            active: i === meta.current_page
                        });
                    }
                }

                // return the result
                return res;
            },
            prev: function() {
                let meta = this.meta;

                if(meta.current_page) {
                    if(meta.current_page > 1) {
                        return meta.current_page - 1;
                    } else {
                        return null;
                    }
                } else {
                    return null;
                }
            },
            next: function() {
                let meta = this.meta;

                if(meta.current_page && meta.last_page) {
                    if(meta.current_page < meta.last_page) {
                        return meta.current_page + 1;
                    } else {
                        return null;
                    }
                } else {
                    return null;
                }
            }
        },
        methods: {
            changeTo(page) {
                if(page !== null) {
                    this.$emit('change', page);
                }
            }
        }
    }
</script>

<style scoped>

</style>