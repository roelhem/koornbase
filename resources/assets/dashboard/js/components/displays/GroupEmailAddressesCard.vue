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
                <subtile-single-input-form :value="item.email_address"
                                           @submit="newValue => updateEmailAddress(index, item.id, newValue)"
                />
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
    import { updateGroupEmailAddress, addEmailAddressToGroup, deleteGroupEmailAddress } from "../../apis/graphql/mutations/groups.graphql";
    import { getGroupDetailsQuery } from "../../apis/graphql/queries/groups.graphql";

    export default {
        components: {
            SubtileFormButton,
            SubtileSingleInputForm,
            BaseIcon,
            TablerCard
        },
        name: "group-email-addresses-card",

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
            items() {
                const emailAddresses = this.group.emailAddresses;

                return emailAddresses.map(emailAddress => {
                    return {
                        ...emailAddress,
                        _showDetails:false,
                    }
                });
            }
        },


        methods: {

            addEmailAddress() {
                const group_id = this.group.id;
                const email_address = this.newEmailAddress;
                const remarks = null;

                this.newEmailAddress = null;

                this.$apollo.mutate({

                    mutation: addEmailAddressToGroup,

                    variables: { group_id, email_address },

                    update:( store, {
                        data: {
                            createGroupEmailAddress: {
                                __typename,
                                id,
                                group_id,
                                email_address,
                                remarks
                            }
                        }
                    } ) => {

                        const data = store.readQuery({
                            query:getGroupDetailsQuery,
                            variables:{ id:group_id },
                        });

                        const newEmailAddressEntry = {
                            __typename, id, email_address, remarks
                        };

                        data.group.emailAddresses.push(newEmailAddressEntry);

                        store.writeQuery({query: getGroupDetailsQuery, data })
                    },

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

                this.$apollo.mutate({

                    mutation: updateGroupEmailAddress,

                    variables: {
                        id: id,
                        email_address:newValue,
                    },

                    update: (store, { data: { updateGroupEmailAddress: { id, group_id, email_address }} }) => {
                        const data = store.readQuery({
                            query:getGroupDetailsQuery,
                            variables: { id:group_id }
                        });

                        data.group.emailAddresses[index].email_address = email_address;

                        store.writeQuery({query: getGroupDetailsQuery, data })
                    },

                    optimisticResponse: {
                        __typename:'Mutation',
                        updateGroupEmailAddress: {
                            __typename:'GroupEmailAddress',
                            id:id,
                            group_id:this.group.id,
                            email_address:newValue
                        }
                    },



                }).then(data => console.log(data))
                    .catch(error => console.error(error));
            },

            deleteEmailAddress(id) {
                const group_id = this.group.id;

                this.$apollo.mutate({

                    mutation: deleteGroupEmailAddress,

                    variables: { id },

                    update:(store, {
                        data: {
                            deleteGroupEmailAddress: {
                                id,
                                group_id
                            }
                        }
                    }) => {
                        const data = store.readQuery({
                            query:getGroupDetailsQuery,
                            variables: {id:group_id }
                        });

                        const emailAddresses = data.group.emailAddresses;

                        const index = emailAddresses.findIndex(emailAddress => emailAddress.id === id);

                        if(index >= 0) {
                            emailAddresses.splice(index, 1);

                            store.writeQuery({query: getGroupDetailsQuery, data});
                        }
                    },

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