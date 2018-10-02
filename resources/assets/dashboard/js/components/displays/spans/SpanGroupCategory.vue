<template>
    <span
            class="display-group-category d-inline-block bg-light rounded"
            v-b-tooltip.hover.bottom.d500="category.description || ''"
    >
        <base-stamp :default-style="category.style"
                    size="xs"
        />
        <span class="display-group-category-label pl-1 pr-2">
            <base-field title="Categorie-naam" name="name">{{ category.name }}</base-field>
        </span>
    </span>
</template>

<script>
    import gql from "graphql-tag";
    import BaseStamp from "../BaseStamp";
    import BaseField from "../BaseField";

    export default {
        components: {
            BaseField,
            BaseStamp
        },
        name: "span-group-category",

        fragment: gql`
            fragment SpanGroupCategory on GroupCategory {
                id
                name
                shortName
                description
                style
            }
        `,

        props: {
            category: {
                type:Object,
                default: function () {
                    return {
                        name:null,
                        shortName:null,
                        description:null,
                        style:null
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>