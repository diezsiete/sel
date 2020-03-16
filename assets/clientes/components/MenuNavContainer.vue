<template>
    <p>
        <!-- use router-link component for navigation. -->
        <!-- specify the link by passing the `to` prop. -->
        <!-- `<router-link>` will be rendered as an `<a>` tag by default -->
        <router-link to="/sel/clientes">Home</router-link>
        <router-link to="/sel/clientes/bar">Go to Bar</router-link>
    </p>
</template>

<script>
    export default {
        name: "MenuNavContainer"
    }
</script>

<style scoped>

</style>