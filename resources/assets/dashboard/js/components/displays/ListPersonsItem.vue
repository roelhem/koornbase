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
                    {{ person.name_first }}
                    <span v-if="person.name_nickname" class="text-muted-dark font-italic ml-1">[ {{ person.name_nickname }} ]</span>
                    {{ person.name_prefix }}
                    {{ person.name_last }}
                </div>
                <div class="small text-muted-dark">
                    <display-membership-status :status="person.membership_status"
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
    import BaseAvatar from "./BaseAvatar";
    import DisplayMembershipStatus from "./DisplayMembershipStatus";
    import BaseIcon from "./BaseIcon";
    import SubtileFormButton from "../inputs/subtile/SubtileFormButton";

    export default {
        components: {
            SubtileFormButton,
            BaseIcon,
            DisplayMembershipStatus,
            BaseAvatar
        },
        name: "list-persons-item",

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