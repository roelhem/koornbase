<template>
    <tabler-card :icon="icon" v-bind="$attrs" v-on="$listeners" no-body>

        <template slot="title">

            <slot name="title">{{ title }}</slot>

            <span v-if="user.name" class="text-muted-dark font-italic">
                '<base-field title="Gebruikersnaam" name="name">{{ user.name }}</base-field>'
            </span>

        </template>


        <detail-view in-card>

            <detail-entry label="Gebruikersnaam">{{ user.name }}</detail-entry>
            <detail-entry label="E-mailadres">{{ user.email }}</detail-entry>
            <detail-entry label="Gekoppelde Persoon">
                <person-span v-if="user.person" :person="user.person" />
                <span v-else class="text-muted font-italic">
                    (Geen persoon gekoppeld)
                </span>
            </detail-entry>

        </detail-view>

    </tabler-card>
</template>

<script>
    import gql from "graphql-tag";
    import TablerCard from "../layouts/cards/TablerCard";
    import BaseField from "./BaseField";
    import DetailView from "../layouts/cards/DetailView";
    import DetailEntry from "../layouts/cards/DetailEntry";
    import PersonSpan from "./PersonSpan";

    export default {

        name: "user-detail-card",

        components: {
            PersonSpan,
            DetailEntry,
            DetailView,
            BaseField,
            TablerCard
        },

        fragment: gql`
            fragment UserDetailCard on User {
                id
                name
                email
                person {
                    id
                    ...PersonSpan
                }
            }
            ${PersonSpan.fragment}
        `,

        props: {
            user: {
                type:Object,
                required:true,
                default:function() {
                    return {
                        name:null,
                        email:null
                    };
                }
            },

            title: {
                type:String,
                default:"Gebruiker"
            },

            icon: {
                type:String,
                default:"user"
            }
        }

    }
</script>

<style scoped>

</style>