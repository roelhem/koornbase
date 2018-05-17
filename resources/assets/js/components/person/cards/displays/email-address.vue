<template>

    <span>
        <data-display>{{ item.email_address }}</data-display>

        <a :href="link" class="btn btn-icon btn-sm">
            <i class="fa fa-envelope-square"></i>
        </a>

    </span>

</template>

<script>
    export default {
        name: "email-address",
        props: {
            item:Object,
            person:Object
        },

        computed: {
            link: function() {
                const address = this.item.email_address;
                let name = null;
                if(this.person && this.person.name && this.person.name.full) {
                    name = this.person.name.full;
                }
                return 'mailto:'+name+' <'+address+'>';
            }
        }
    }
</script>

<style scoped>

</style>