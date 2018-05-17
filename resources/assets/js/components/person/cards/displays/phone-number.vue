<template>
    <span class="tracking-wide">
        <data-display v-if="international">{{ formats.international }}</data-display>
        <data-display v-else>{{ formats.display }}</data-display>

        <a :href="link" class="btn btn-icon btn-sm">
            <i class="fa fa-phone-square"></i>
        </a>

    </span>
</template>

<script>
    export default {
        name: "phone-number",
        props: {
            item:Object,
            international:{
                type:Boolean,
                default:false
            }
        },

        computed: {
            formats: function() {
                if(this.item.formats) {
                    return this.item.formats;
                } else {
                    return {
                        display:this.item.phone_number,
                        dailing:this.item.phone_number,
                        international:this.item.phone_number
                    }
                }
            },

            link: function() {
                let format = this.international ? this.items.phone_number : this.formats.dailing;
                return 'tel:'+format;
            }
        }
    }
</script>

<style scoped>

</style>