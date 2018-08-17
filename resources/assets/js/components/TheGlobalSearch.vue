<template>
    <div>
        <tabler-input-icon append="search">
            <b-form-input class="header-search" placeholder="Zoeken..." tabindex="1" type="search" />
        </tabler-input-icon>
    </div>
</template>

<script>
    import TablerInputIcon from "./TablerInputIcon";

    export default {
        components: {TablerInputIcon},
        name: "the-global-search"
    }
</script>

<style scoped>

</style>