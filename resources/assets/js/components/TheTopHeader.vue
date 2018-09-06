<template>

    <div class="header py-4">
        <b-container>

            <div class="d-flex">

                <the-header-brand></the-header-brand>


                <!-- PLACE FOR THE LOGIN AND ACCOUNT INFO -->
                <div class="d-flex order-lg-2 ml-auto">

                    <notification-dropdown />
                    <user-dropdown />

                </div>

                <!-- HEADER TOGGLER FOR SMALL SCREENS -->
                <a href="#" class="header-toggler d-lg-none ml-3 ml-lg-0" data-toggle="collapse" data-target="#headerMenuCollapse">
                    <span class="header-toggler-icon"></span>
                </a>

            </div>

        </b-container>
    </div>

</template>

<script>
    import TheHeaderBrand from "./TheHeaderBrand";
    import UserDropdown from "./UserDropdown";
    import NotificationDropdown from "./NotificationDropdown";

    export default {
        components: {
            NotificationDropdown,
            UserDropdown,
            TheHeaderBrand
        },
        name: "the-top-header",
    }
</script>

<style scoped>

</style>