<template>
    <tr>
        <th title="Diploma/certificaat">
            <base-icon icon="certificate" from="fa" />
        </th>


        <td>
            <span-certificate-category :category="certificate.category" />
        </td>


        <td>
            <span class="small">
                <span class="text-muted">sinds</span>
                <base-field title="Certificaat geldig sinds" name="certificate.validDate" v-if="certificate.validDate" class="text-muted-dark">
                    {{ certificate.validDate | date('sm') }}
                </base-field>
                <span v-else class="text-muted font-italic">(onbekend)</span>
            </span>
        </td>


        <td>
            <span v-if="certificate.expireDate" class="small">
                <span class="text-muted">tot</span>
                <base-field title="Certificaat vervalt op" name="expireDate" class="text-muted-dark">
                    {{ certificate.expireDate | date('sm') }}
                </base-field>
            </span>
            <span v-else class="small text-muted font-italic">(verloopt niet)</span>
        </td>


    </tr>
</template>

<script>
    import gql from "graphql-tag";
    import BaseIcon from "./BaseIcon";
    import DisplayFilters from "../../utils/filters/display";
    import SpanCertificateCategory from "./spans/SpanCertificateCategory";
    import BaseField from "./BaseField";

    export default {
        components: {
            BaseField,
            SpanCertificateCategory,
            BaseIcon
        },

        fragment:gql`
            fragment CertificateTableSmallRow on Certificate {
                id
                category {
                    id
                    ...SpanCertificateCategory
                }
                validDate
                expireDate
            }
            ${SpanCertificateCategory.fragment}
        `,

        filters:DisplayFilters,

        name: "certificate-table-small-row",

        props:{
            certificate:{
                type:Object,
                required:true
            }
        }
    }
</script>

<style scoped>

</style>