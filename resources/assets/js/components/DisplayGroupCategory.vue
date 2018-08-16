<template>
    <span class="display-group-category d-inline-block bg-light rounded" v-b-tooltip.hover.bottom.d500="category.description">
        <base-stamp :default-style="category.style"
                    size="xs"
        />
        <span class="display-group-category-label pl-1 pr-2">
            {{ category.name }}
        </span>
    </span>
</template>

<script>
    import BaseStamp from "./BaseStamp";

    export default {
        components: {
            BaseStamp
        },
        name: "display-group-category",

        props: {
            category: {
                type:Object,
                default: function () {
                    return {
                        name:null,
                        name_short:null,
                        description:null,
                        style:null,
                        groups_count:0
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>