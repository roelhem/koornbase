<template>

    <tabler-card :title="title" :no-header="noHeader">

        <div>
            <tabler-alert v-for="(message, index) in messages"
                          :key="'message_'+index"
                          variant="danger"
                          icon="alert-triangle"
                          dismissible
                          @dismiss="$emit('dismiss-message', index)"
            >{{ message }}</tabler-alert>
        </div>

        <slot />

        <template slot="footer">
            <div class="text-right">
                <b-button :variant="submitVariant" @click="$emit('submit')">{{ submitText }}</b-button>
                <b-button :variant="resetVariant" @click="$emit('reset')">{{ resetText }}</b-button>
            </div>
        </template>

    </tabler-card>


</template>

<script>
    import TablerCard from "../TablerCard";
    import TablerAlert from "../TablerAlert";

    export default {
        components: {
            TablerAlert,
            TablerCard
        },
        name: "form-layout-card",

        props: {
            title:String,
            messages: {
                type:Array,
                default:function() {
                    return [];
                }
            },

            submitText: {
                type:String,
                default:"Versturen"
            },
            submitVariant: {
                type:String,
                default:"primary"
            },
            resetText: {
                type:String,
                default:"Resetten"
            },
            resetVariant: {
                type:String,
                default:"secondary"
            }
        },

        data: function() {
            return {
                testAlertDismissed: false
            };
        },

        computed: {
            noHeader() {
                return !this.title;
            }
        }
    }
</script>

<style scoped>

</style>