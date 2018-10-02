<template>
    <table class="table table-sm table-hover" :class="{'card-table':inCard}">

        <tbody>
            <certificate-table-small-row
                    v-for="certificate in certificates"
                    :key="certificate.id"
                    :certificate="certificate"
            />
        </tbody>

    </table>
</template>

<script>
    import CertificateTableSmallRow from "./CertificateTableSmallRow";

    export default {
        components: {CertificateTableSmallRow},
        name: "certificate-table-small",

        props: {
            certificates:{
                type:Array,
                default:function() {
                    return [];
                }
            },

            inCard:{
                type:Boolean,
                default:false
            }
        },
    }
</script>

<style scoped>

</style>