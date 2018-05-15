<template>

    <div class="container my-4">
        <ul class="navbar">
            <li class="nav-item">
                <a v-for="page in pages" class="nav-link" :href="page.name" @click.prevent="changePage(page)">{{ page.label }}</a>
            </li>
        </ul>
    </div>


</template>

<script>
    export default {

        props: {
            pages:Array
        },

        methods: {
            changePage(page) {
                this.$emit('change-page', page);
            }
        }

    }
</script>

<style scoped>

</style>