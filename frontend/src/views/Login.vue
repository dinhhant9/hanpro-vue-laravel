<template>
  <v-app id="inspire">
    <v-content>
      <v-container class="fill-height" fluid>
        <v-row align="center" justify="center">
          <v-col cols="12" sm="8" md="4">
            <v-card class="elevation-12">
              <v-toolbar color="primary" dark flat>
                <v-toolbar-title>Đăng nhập</v-toolbar-title>
              </v-toolbar>
              <v-card-text>
                <v-form>
                  <v-text-field
                    label="Email"
                    name="login"
                    prepend-icon="mdi-pencil"
                    type="text"
                    v-model="email"
                  ></v-text-field>

                  <v-text-field
                    id="password"
                    label="Mật khẩu"
                    name="password"
                    prepend-icon="mdi-pencil"
                    type="password"
                    v-model="password"
                  ></v-text-field>
                </v-form>
              </v-card-text>
              <v-card-actions style="padding-bottom: 20px;">
                <v-btn style="margin: auto;" color="primary" :loading="loginLoading" @click="handleLogin">Đăng nhập</v-btn>
              </v-card-actions>
              <center>
                <p v-show="loginFail" style="padding-bottom: 20px">Đăng nhập không thành công</p>
              </center>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
import axios from "axios";
export default {
  data: () => ({
    email: "",
    password: "",
    loginLoading: false,
    loginFail: false,
    drawer: null
  }),
  methods: {
    handleLogin() {
        this.loginLoading = true;
      axios.post("login", {
          email: this.email,
          password: this.password,
          remember_me: true
        })
        .then(res => {
          this.loginLoading = false;
          this.$store.commit('logIn', res.data.access_token);
        })
        .catch(() => {
          this.loginLoading = false;
            this.loginFail = true;
        });
      // this.$store.commit('logIn')
    }
  }
};
</script>
