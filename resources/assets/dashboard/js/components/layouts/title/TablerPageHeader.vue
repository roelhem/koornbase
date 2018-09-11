<template>

    <div class="page-header">
        <div class="page-title">
            <slot name="title">
                {{ title }}
            </slot>
        </div>
        <div class="page-subtitle">
            <slot name="subtitle">{{ subtitle }}</slot>
        </div>
        <div class="page-options">
            <slot name="options" />
        </div>
        <tabler-breadcrumb :items="breadcrumb" v-if="!noBreadcrumb">
            <template slot="before">
                <tabler-breadcrumb-item href="/"><em>KoornBase</em></tabler-breadcrumb-item>
                <tabler-breadcrumb-item to="/" icon="dashboard" text="Dashboard" />
            </template>
            <template slot="after">
                <slot name="breadcrumb" />
            </template>
        </tabler-breadcrumb>
    </div>

</template>

<script>
    import TablerBreadcrumb from "./TablerBreadcrumb";
    import TablerBreadcrumbItem from "./TablerBreadcrumbItem";

    export default {
        components: {
            TablerBreadcrumbItem,
            TablerBreadcrumb,
        },
        name: "tabler-page-header",

        props: {
            title:String,
            subtitle:String,
            noBreadcrumb:{
                type:Boolean,
                default:false,
            },
            breadcrumb:Array
        }
    }
</script>

<style scoped>

</style>