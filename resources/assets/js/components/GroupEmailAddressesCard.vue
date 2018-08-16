<template>

    <tabler-card title="E-mailadressen" no-body>

        <b-table class="card-table"
                 :items="items"
                 :fields="fields"
        >

            <template slot="toggle" slot-scope="{ item }">
                <a href="javascript:void(0);" class="btn btn-icon btn-subtile-cyan">
                    <base-icon :icon="item._showDetails ? 'chevron-down' : 'chevron-right'" from="fe" />
                </a>
            </template>

            <template slot="email_address" slot-scope="{ item }">
                <subtile-single-input-form :value="item.email_address" />
            </template>

            <template slot="actions" slot-scope="{ item }">
                <subtile-form-button icon="trash" color="red" />
            </template>

            <template slot="row-details" slot-scope="{ item }">
                <b-row>
                    <b-col>
                        <h3>Instellingen</h3>
                    </b-col>
                    <b-col>
                        <textarea class="form-control">{{ item.remarks }}</textarea>

                    </b-col>
                </b-row>

            </template>

        </b-table>

    </tabler-card>

</template>

<script>
    import TablerCard from "./TablerCard";
    import BaseIcon from "./BaseIcon";
    import SubtileSingleInputForm from "./forms/subtile/SubtileSingleInputForm";
    import SubtileFormButton from "./forms/subtile/SubtileFormButton";

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

        computed: {

            items() {
                return this.group.emailAddresses.map(emailAddress => {
                    return {
                        ...emailAddress,
                        _showDetails:false
                    };
                });
            }

        }
    }
</script>

<style scoped>

</style>