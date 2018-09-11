<template>

    <b-card no-body>
        <tabler-dimmer :active="$apollo.queries.certificates.loading">
            <table class="table table-sm card-table">
                <tbody v-if="certificateCount === 0">
                    <tr>
                        <td class="text-center">
                            <span class="text-muted font-italic">(Geen geldige certificaten)</span>
                        </td>
                    </tr>
                    <tr v-if="$apollo.queries.certificates.loading"><td>&nbsp;</td></tr>
                </tbody>
                <tbody v-else>
                    <tr v-for="certificate in certificates.data">
                        <th title="Diploma/certificaat">
                            <base-icon icon="certificate" from="fa" />
                        </th>
                        <td>
                            <display-certificate-category
                                    :name="certificate.category.name"
                                    :name_short="certificate.category.name_short"
                                    :description="certificate.category.description"
                            />
                        </td>
                        <td>
                            <span class="small">
                                <span class="text-muted">sinds</span>
                                <data-display title="Certificaat geldig sinds" v-if="certificate.valid_since" class="text-muted-dark">
                                    {{ certificate.valid_since | date('sm') }}
                                </data-display>
                                <span v-else class="text-muted font-italic">
                                    (onbekend)
                                </span>
                            </span>
                        </td>
                        <td>
                            <span v-if="certificate.valid_till" class="small">
                                <span class="text-muted">tot</span>
                                <data-display title="Certificaat vervalt op" class="text-muted-dark">
                                    {{ certificate.valid_till | date('sm') }}
                                </data-display>
                            </span>
                            <span v-else class="small text-muted font-italic">
                                (verloopt niet)
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </tabler-dimmer>
    </b-card>

</template>

<script>

    import { getActiveCertificatesOfPerson } from "../../apis/graphql/queries/certificates.graphql";
    import BaseIcon from "./BaseIcon";
    import DisplayFilters from "../../utils/filters/display";
    import TablerDimmer from "../layouts/cards/TablerDimmer";
    import DataDisplay from "./DataDisplay";
    import DisplayCertificateCategory from "./DisplayCertificateCategory";


    export default {

        filters: DisplayFilters,

        components: {
            DisplayCertificateCategory,
            DataDisplay,
            TablerDimmer,
            BaseIcon
        },


        apollo: {
            certificates:{
                query: getActiveCertificatesOfPerson,
                variables() {
                    return {
                        id: this.personId
                    }
                }
            }
        },


        data:function() {
            return {
                certificates:{
                    total:0,
                    data:[]
                }
            };
        },

        props:{
            personId:{
                type:[String,Number],
                required:true
            }
        },

        computed:{
            certificateCount() {
                if(this.certificates && this.certificates.data) {
                    return this.certificates.data.length;
                }
                return 0;
            }
        },


        name: "show-valid-certificates-of-person-card-small"
    }
</script>

<style scoped>

</style>