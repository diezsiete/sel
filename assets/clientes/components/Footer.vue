<template>
    <div class="container-fluid">
        <div class='d-flex'>
            <div>
                <div class='mb-2 links'>
                    <a href="https://www.telerik.com/purchase/license-agreement/kendo-ui?utm_medium=cpm&utm_source=vscode-app&utm_campaign=kendo-ui-vue-awareness-license-agreement">License Agreement</a>
                </div>
                <div class='copyright'>
                    Copyright © {{currentYear}} Software Corporation and/or its subsidiaries or affiliates. All Rights Reserved.
                </div>
            </div>
            <div class='d-flex ml-auto'>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Footer",
        data: function() {
            return {
                currentYear: new Date().getFullYear()
            }
        }
    }
</script>

<style scoped>

</style>