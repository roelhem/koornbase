<template>
    <tabler-card no-header>
        <b-media>
            <base-avatar
                    slot="aside"
                    v-bind="user.avatar"
                    size="xxl"
                    class="mr-5"
                    default-style="person-default"
            />
            <h4 class="m-0">
                <base-field title="Naam" name="name_display" :value="user.name_display" />
            </h4>
            <p class="text-muted mb-0">
                <base-field title="Inlog E-mail" name="email" :value="user.email" />
                <small v-if="user.person" class="text-muted-dark">
                    (<base-field title="Gebruikersnaam" name="name" :value="user.name" class="font-italic" />)
                </small>
            </p>
        </b-media>
    </tabler-card>
</template>

<script>
    import TablerCard from "../layouts/cards/TablerCard";
    import BaseAvatar from "./BaseAvatar";
    import BaseField from "./BaseField";

    export default {

        props: {
            user:{
                type:Object,
                default:function() {
                    return {
                        avatar:{}
                    };
                }
            }
        },

        components: {
            BaseField,
            BaseAvatar,
            TablerCard
        },
        name: "display-user-media-card"
    }
</script>

<style scoped>

</style>