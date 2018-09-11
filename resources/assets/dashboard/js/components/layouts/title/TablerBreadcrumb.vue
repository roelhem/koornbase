<template>

    <b-breadcrumb>
        <slot name="before" />
        <slot>
            <tabler-breadcrumb-item v-for="(item, index) in items"
                                    :key="'breadcrumb-item-'+index"
                                    v-bind="item"
            />
        </slot>
        <slot name="after" />
    </b-breadcrumb>

</template>

<script>
    import TablerBreadcrumbItem from "./TablerBreadcrumbItem";

    export default {
        components: {TablerBreadcrumbItem},
        name: "tabler-breadcrumb",

        props: {
            items: {
                type:Array,
                default:function() {
                    return [];
                }
            }
        }
    }
</script>

<style scoped>

    .breadcrumb {
        background-color: transparent;
        margin-bottom: 0rem;
        padding: 0.5rem 1rem;
    }

</style>