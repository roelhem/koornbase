<template>

    <span>
        <template v-if="person">
            <base-avatar v-if="person.avatar"
                         :letters="person.avatar.letters"
                         :image="person.avatar.image"
                         size="sm"
                         class="mr-1"
                         default-style="person-default"
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
    import BaseAvatar from "./BaseAvatar";
    import SpanPersonName from "./spans/SpanPersonName";

    export default {
        name: "person-span",

        components: {
            SpanPersonName,
            BaseAvatar
        },

        fragment:gql`
            fragment PersonSpan on Person {
                avatar {
                    letters
                    image
                }
                ...SpanPersonName
            }
            ${SpanPersonName.fragment}
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