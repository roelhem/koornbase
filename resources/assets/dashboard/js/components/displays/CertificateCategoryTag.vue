<template>

    <base-tag v-bind="$attrs"
              v-on="$listeners"
              v-b-tooltip.hover.html="tooltip"
              rounded
    >
        <span class="tag-avatar avatar bg-transparent"><base-icon class="text-muted" icon="certificate" from="fa" /></span>
        {{ certificateCategory.shortName }}
    </base-tag>

</template>

<script>
    import gql from "graphql-tag";
    import BaseTag from "./BaseTag";
    import BaseIcon from "./BaseIcon";

    export default {
        components: {
            BaseIcon,
            BaseTag},
        name: "certificate-category-tag",

        fragment:gql`
            fragment CertificateCategoryTag on CertificateCategory {
                id
                shortName
                name
                description
            }
        `,

        props:{
            certificateCategory:{
                type:Object,
                required:true,
                default() { return {id:null, shortName:null}; }
            }
        },

        computed:{
            tooltip() {
                const name = this.certificateCategory.name || '';
                const description = this.certificateCategory.description || '';
                return `<h5>${name}</h5>${description}`
            }
        }
    }
</script>

<style scoped>

</style>