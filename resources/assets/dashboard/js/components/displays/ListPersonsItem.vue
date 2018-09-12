<template>

    <li class="list-separated-item">

        <b-row>
            <div class="col-auto">
                <base-avatar :image="person.avatar.image"
                             :letters="person.avatar.letters"
                             default-style="person-default"
                             size="md"
                />
            </div>



            <b-col>
                <div>
                    <span-person-name :person="person" with-nickname />
                </div>
                <div class="small text-muted-dark">
                    <span-membership-status :status="person.membership_status"
                                            :since="person.membership_status_since"
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
    import BaseAvatar from "./BaseAvatar";
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
            BaseAvatar
        },
        name: "list-persons-item",

        fragment:gql`
            fragment ListPersonsItem on Person {
                id
                ...PersonAvatar
                ...PersonNameSpan
                ...PersonMembershipStatus
            }
            ${fragments.PersonAvatar}
            ${fragments.PersonNameSpan}
            ${fragments.PersonMembershipStatus}
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