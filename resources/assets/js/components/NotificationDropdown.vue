<template>

    <div class="dropdown d-none d-md-flex">
        <a class="nav-link icon" data-toggle="dropdown">
            <base-icon icon="bell" from="fe"></base-icon>
            <span class="nav-unread"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
            <a href="#" class="dropdown-item d-flex">
                <span class="stamp stamp-md mr-3 bg-orange">
                    <base-icon icon="alert-octagon" from="fe"></base-icon>
                </span>
                <div>
                    Hier een <strong>notificatie</strong> van de gebruiker.
                    <div class="small text-muted">10 minuten geleden...</div>
                </div>
            </a>
            <a href="#" class="dropdown-item d-flex">
                <span class="stamp stamp-md mr-3 bg-purple-light">
                    <base-icon icon="slack" from="fe"></base-icon>
                </span>
                <div>
                    Hier een <strong>notificatie</strong> van de gebruiker.
                    <div class="small text-muted">10 minuten geleden...</div>
                </div>
            </a>
            <a href="#" class="dropdown-item d-flex">
                <span class="stamp stamp-md mr-3 bg-pink">
                    <base-icon icon="shield" from="fe"></base-icon>
                </span>
                <div>
                    Hier een <strong>notificatie</strong> van de gebruiker.
                    <div class="small text-muted">10 minuten geleden...</div>
                </div>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item d-flex">Al het andere bekijken.</a>
        </div>
    </div>

</template>

<script>
    import BaseIcon from "./BaseIcon";

    export default {
        components: {BaseIcon},
        name: "notification-dropdown"
    }
</script>

<style scoped>

</style>