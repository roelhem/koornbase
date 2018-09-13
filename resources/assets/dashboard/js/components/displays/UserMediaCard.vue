<template>
    <tabler-card no-header>
        <b-media>
            <user-avatar slot="aside" :user="user" size="xxl" class="mr-5" />
            <h4 class="m-0">
                <span-person-name v-if="user.person" :person="user.person" />
                <base-field v-else title="Gebruikersnaam" name="name" :value="user.name" />
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
    import BaseField from "./BaseField";
    import SpanPersonName from "./spans/SpanPersonName";
    import UserAvatar from "./UserAvatar";

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
            UserAvatar,
            SpanPersonName,
            BaseField,
            TablerCard
        },
        name: "display-user-media-card"
    }
</script>

<style scoped>

</style>