<template>

    <li class="list-separated-item">

        <b-row>
            <div class="col-auto">
                <person-avatar :person="person" size="md" />
            </div>



            <b-col>
                <div>
                    <span-person-name :person-name="person.name" with-nickname />
                </div>
                <div class="small text-muted-dark">
                    <span-membership-status :membership-status="person.membershipStatus"
                                            date-size="sm"
                    />
                </div>
            </b-col>



            <div class="col-auto" v-if="removable">
                <subtile-form-button icon="trash" color="red" @click="remove" />
            </div>

        </b-row>

    </li>

</template>

<script>
    import gql from "graphql-tag";
    import fragments from "../../apis/graphql/queries/fragments";
    import PersonAvatar from "./PersonAvatar";
    import SpanMembershipStatus from "./spans/SpanMembershipStatus";
    import BaseIcon from "./BaseIcon";
    import SubtileFormButton from "../inputs/subtile/SubtileFormButton";
    import SpanPersonName from "./spans/SpanPersonName";

    export default {
        components: {
            SpanPersonName,
            SubtileFormButton,
            BaseIcon,
            SpanMembershipStatus,
            PersonAvatar
        },
        name: "list-persons-item",

        fragment:gql`
            fragment ListPersonsItem on Person {
                id
                ...PersonAvatar
                name {...SpanPersonName}
                membershipStatus {...SpanMembershipStatus}
            }
            ${PersonAvatar.fragment}
            ${SpanPersonName.fragment}
            ${SpanMembershipStatus.fragment}
        `,

        props: {
            person: {
                type:Object,
            },
            removable: {
                type:Boolean,
                default:false,
            }
        },

        methods: {
            remove() {
                this.$emit('remove', this.person);
            }
        }
    }
</script>

<style scoped>

</style>