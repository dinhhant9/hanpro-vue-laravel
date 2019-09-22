<template>
  <v-app>
    <div v-if="checkLogin()">
    <v-navigation-drawer
      v-model="drawer"
      app
    >
      <v-list-item>
        <v-list-item-content>
          <v-list-item-title class="title">
            Application
          </v-list-item-title>
          <v-list-item-subtitle>
            subtext
          </v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>

      <v-divider></v-divider>

      <v-list
        dense
        nav
      >
        <v-list-item
          v-for="item in items"
          :key="item.title"
          link
          @click="chuyenTrang(item)"
        >
          <v-list-item-icon>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>{{ item.title }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar
      app
      color="indigo"
      dark
    >
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title>Hân pro</v-toolbar-title>
      <div class="flex-grow-1"></div>
      <v-toolbar-items>
        <v-btn text @click="handleLogout">Đăng xuất</v-btn>
      </v-toolbar-items>
    </v-app-bar>

    <v-content>
      <router-view />
    </v-content>

    <v-footer
      color="indigo"
      app
    >
      <span class="white--text">&copy; Đình Hân 2019</span>
    </v-footer>
    </div>
    <div v-else>
      <Login />
    </div>
  </v-app>
</template>

<script>
import Login from './views/Login'
export default {
  name: 'App',
  components: {
    Login
  },
  data: () => ({
    drawer: null,
    items: [
          { title: 'Trang chủ', icon: 'mdi-view-dashboard', component: 'home' },
          { title: 'Lịch', icon: 'mdi-image', component: 'calendar' },
          { title: 'Sự kiện', icon: 'mdi-image', component: 'event' },
          { title: 'Thông tin', icon: 'mdi-help-box', component: 'about' },
        ],
  }),
  created() {
    if (!this.$store.getters.isLogin) {
      this.$router.replace({name : 'home'});
    }
  },
  methods: {
    chuyenTrang(item) {
      this.$router.push({ name: item.component});
    },
    checkLogin() {
      return this.$store.getters.isLogin;
    },
    handleLogout() {
      this.$router.replace({name : 'home'});
      this.$store.commit('logOut');
    }
  }
};
</script>
