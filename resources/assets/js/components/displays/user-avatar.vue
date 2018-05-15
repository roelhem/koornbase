<template>

    <span class="avatar avatar-blue"
          :class="usedPart === 'placeholder' ? placeholder : null"
          :style="style">
        {{ usedPart === 'letters' ? letters : null }}
    </span>

</template>

<script>
    export default {
        name: "user-avatar",

        props: {
            letters:null,
            placeholder:false,
            image:null
        },

        computed: {
            usedPart: function() {
                if(this.image) {
                    return 'image';
                } else if(this.placeholder) {
                    return 'placeholder';
                } else {
                    return 'letters';
                }
            },
            style: function() {
                if(this.image) {
                    return 'background-image:url("'+this.image+'");';
                } else {
                    return null;
                }
            }
        }
    }
</script>

<style scoped>

</style>