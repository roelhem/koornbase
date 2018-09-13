<template>

    <span>
        <template v-if="person">
            <person-avatar v-if="person.avatar"
                           :person="person"
                           size="sm"
                           class="mr-1"
            />
            <span-person-name :person="person"
                              :formal="formal"
                              :short="short"
                              :full="full"
                              :with-nickname="withNickname"
            />
        </template>
    </span>

</template>

<script>
    import gql from "graphql-tag";
    import PersonAvatar from "./PersonAvatar";
    import SpanPersonName from "./spans/SpanPersonName";

    export default {
        name: "person-span",

        components: {
            SpanPersonName,
            PersonAvatar
        },

        fragment:gql`
            fragment PersonSpan on Person {
                ...PersonAvatar
                ...SpanPersonName
            }
            ${SpanPersonName.fragment}
            ${PersonAvatar.fragment}
        `,

        props: {
            person:Object,

            formal:{
                type:Boolean,
                default:false
            },

            short:{
                type:Boolean,
                default:false,
            },

            full:{
                type:Boolean,
                default:false
            },

            withNickname:{
                type:Boolean,
                default:false
            }
        },
    }
</script>

<style scoped>

</style>