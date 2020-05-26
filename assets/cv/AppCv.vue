<template>
    <v-app>
        <v-content>
            <v-container fluid>
                <v-row>
                    <v-col cols="12" md="3">
                        <v-card elevation="2" width="256">
                            <v-navigation-drawer permanent floating>
                                <v-list dense>
                                    <v-list-item v-for="item in items" :key="item.title" :to="{path: item.route}">
                                        <v-list-item-icon>
                                            <v-icon>{{ item.icon }}</v-icon>
                                        </v-list-item-icon>

                                        <v-list-item-content>
                                            <v-list-item-title>{{ item.title }}</v-list-item-title>
                                        </v-list-item-content>
                                    </v-list-item>
                                </v-list>
                            </v-navigation-drawer>
                        </v-card>
                    </v-col>
                    <v-col cols="12" md="9">
                        <router-view></router-view>
                    </v-col>
                </v-row>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
    export default {
        name: "AppCv",
        data: () => ({
            items: [
                { title: 'Datos básicos', icon: 'mdi-view-dashboard', route: '/sel/cv/datos-basicos'},
                { title: 'Estudios', icon: 'mdi-image', route: '/sel/cv/estudios' },
            ],
            right: null,
        }),
    }
</script>

<style lang="scss">
    .v-list-item:hover{
        text-decoration: none;
    }
</style>