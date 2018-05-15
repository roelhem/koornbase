<template>

    <div>
        <div class="btn-group">
            <a class="btn btn-link"
               :class="{'disabled':!links.first}"
               :href="links.first"
               @click.prevent="linkClickHandler">
                Eerste
            </a>
            <a class="btn btn-link"
               :class="{'disabled':!links.prev}"
               :href="links.prev"
               @click.prevent="linkClickHandler">
                Vorige
            </a>
            <a class="btn btn-link"
               :class="{'disabled':!links.next}"
               :href="links.next"
               @click.prevent="linkClickHandler">
                Volgende
            </a>
            <a class="btn btn-link"
               :class="{'disabled':!links.last}"
               :href="links.last"
               @click.prevent="linkClickHandler">
                Laatste
            </a>
        </div>
    </div>

</template>

<script>
    export default {
        name: "people-search-pager.vue",
        props: {
            links:Object
        },
        methods: {
            linkClickHandler(event) {
                if(event.target.href) {
                    this.$emit('change-link', event.target.href);
                }
            }
        }
    }
</script>

<style scoped>

</style>