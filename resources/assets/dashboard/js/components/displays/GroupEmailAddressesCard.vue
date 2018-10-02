<template>

    <tabler-card title="E-mailadressen" no-body>

        <b-table class="card-table"
                 :items="items"
                 :fields="fields"
        >

            <template slot="toggle" slot-scope="{ item, toggleDetails }">
                <a href="javascript:void(0);" @click="toggleDetails" class="btn btn-icon btn-subtile-cyan">
                    <base-icon :icon="item._showDetails ? 'chevron-down' : 'chevron-right'"
                               from="fe"
                    />
                </a>
            </template>

            <template slot="email_address" slot-scope="{ item, index }">
                <subtile-single-input-form :value="item.emailAddress.email"
                                           @submit="newValue => updateEmailAddress(index, item.id, newValue)"
                ><email-address-span :email-address="item.emailAddress" with-name with-button /></subtile-single-input-form>
            </template>

            <template slot="actions" slot-scope="{ item }">
                <subtile-form-button icon="trash"
                                     color="red"
                                     @click="deleteEmailAddress(item.id)"
                />
            </template>

            <template slot="row-details" slot-scope="{ item }">
                <b-row>
                    <b-col>
                        {{ item.remarks }}
                    </b-col>
                </b-row>

            </template>

            <template slot="bottom-row" slot-scope="{ fields }">
                <td class="p-1">

                </td>
                <td class="p-1">
                    <b-form-input placeholder="Nieuw E-mailadres aan deze groep toevoegen..."
                                  v-model="newEmailAddress"
                    />
                </td>
                <td class="p-1">
                    <subtile-form-button icon="plus" color="green" @click="addEmailAddress" />
                </td>
            </template>

        </b-table>

    </tabler-card>

</template>

<script>
    import TablerCard from "../layouts/cards/TablerCard";
    import BaseIcon from "./BaseIcon";
    import SubtileSingleInputForm from "../inputs/subtile/SubtileSingleInputForm";
    import SubtileFormButton from "../inputs/subtile/SubtileFormButton";
    import gql from "graphql-tag";
    import EmailAddressSpan from "./EmailAddressSpan";

    export default {
        components: {
            EmailAddressSpan,
            SubtileFormButton,
            SubtileSingleInputForm,
            BaseIcon,
            TablerCard
        },
        name: "group-email-addresses-card",

        fragment: gql`
            fragment GroupEmailAddressesCard on Group {
                emailAddresses {
                    id
                    emailAddress {
                        email
                        ...EmailAddressSpan
                    }
                }
            }
            ${EmailAddressSpan.fragment}
        `,

        props: {
            group: {
                type:Object,
                required:true,
            }
        },

        data() {
            return {
                newEmailAddress:null,
                fields: [
                    {
                        key:'toggle',
                        label:'',
                        thStyle:{'width':'1px'},
                        tdClass:'p-1'
                    },
                    {
                        key:'email_address',
                        label:'E-mailadres',
                        tdClass:'p-1'
                    },
                    {
                        key:'actions',
                        label:'',
                        thStyle:{'width':'1px'},
                        tdClass:'p-1'
                    }
                ]
            }
        },

        /*
        watch: {

            group: {
                handler(newVal) {
                    if(!newVal || !newVal.emailAddresses) {
                        this.items = [];
                    }
                    const emailAddresses = newVal.emailAddresses;
                    this.items = emailAddresses.map(emailAddress => {
                        return {
                            ...emailAddress,
                            _showDetails:false,
                        };
                    });
                },
                deep:true
            }

        },*/

        computed: {

            emailAddresses() {
                if(this.group && this.group.emailAddresses) {
                    return this.group.emailAddresses;
                }
                return [];
            },

            items() {

                const emailAddresses = this.emailAddresses;

                if(emailAddresses) {
                    return emailAddresses.map(emailAddress => {
                        return {
                            ...emailAddress,
                            _showDetails: false,
                        }
                    });
                }

                return [];
            }
        },


        methods: {

            addEmailAddress() {
                const group_id = this.group.id;
                const email_address = this.newEmailAddress;
                const remarks = null;

                this.newEmailAddress = null;

                this.$apollo.mutate({

                    mutation: gql`
                        mutation addEmailAddressToGroup($group_id:ID!, $email_address:Email!) {
                            createGroupEmailAddress(group_id:$group_id, email_address:$email_address) {
                                id
                                group_id
                                email_address
                                remarks
                            }
                        }
                    `,

                    variables: { group_id, email_address },

                    optimisticResponse: {
                        __typename:'Mutation',
                        createGroupEmailAddress: {
                            __typename:'GroupEmailAddress',
                            id:-1, group_id, email_address, remarks
                        }
                    },

                }).then(data => console.log(data))
                    .catch(error => {
                        this.newEmailAddress = email_address;
                        console.error(error);
                    });
            },

            updateEmailAddress(index, id, newValue) {
                const email_address = newValue;

                this.$apollo.mutate({

                    mutation: gql`
                        mutation updateGroupEmailAddressEmailAddress($id:ID!, $email_address:Email) {
                            updateGroupEmailAddress(id:$id, email_address:$email_address) {
                                id email_address
                            }
                        }
                    `,

                    variables: { id, email_address },

                    optimisticResponse: {
                        __typename:'Mutation',
                        updateGroupEmailAddress: {
                            __typename:'GroupEmailAddress',
                            id, email_address
                        }
                    },



                }).then(data => console.log(data))
                    .catch(error => console.error(error));
            },

            deleteEmailAddress(id) {
                const group_id = this.group.id;

                this.$apollo.mutate({

                    mutation: gql`
                        mutation deleteGroupEmailAddress($id:ID!) {
                            deleteGroupEmailAddress(id:$id) {
                                id group_id
                            }
                        }
                    `,

                    variables: { id },

                    optimisticResponse: {
                        __typename:'Mutation',
                        deleteGroupEmailAddress: {
                            __typename:'GroupEmailAddress',
                            id, group_id
                        }
                    },

                }).then(data => console.log(data))
                    .catch(error => console.error(error));
            }

        }
    }
</script>

<style scoped>

</style>