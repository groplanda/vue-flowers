<template>
  <section class="account section">
    <Loading v-if="!userData" />
    <div class="container account__container" v-else>
      <h1 class="title account__title">Добро пожаловать, {{ userData.name }}</h1>
      <div class="account__row">
        <div class="account__menu">
            <button class="account__menu-btn" :class="{ 'account__menu-btn_active': activeTab === 0 }" @click="activeTab = 0">Мои заказы</button>
            <button class="account__menu-btn" :class="{ 'account__menu-btn_active': activeTab === 1 }" @click="activeTab = 1">Изменить личные данные</button>
            <button class="account__menu-btn" :class="{ 'account__menu-btn_active': activeTab === 2 }" @click="activeTab = 2">Изменить пароль</button>
            <button class="account__menu-btn" @click="onLogout">Выйти</button>
          </div>
        <div class="account__content">
          <div class="account__orders" v-if="activeTab === 0">
            <AccountOrders :orders="orders" v-if="orders.length" />
            <div class="title title_sm account__title" v-else>Заказов пока нет...</div>
          </div>
          <div class="account__form" v-if="activeTab === 1">
            <AccountEdit :user="userData" />
          </div>
          <div class="account__form" v-if="activeTab === 2">
            <AccountChangePassword :user="userData" />
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<script>

import AccountOrders from "./AccountOrders";
import AccountEdit from "./AccountEdit";
import AccountChangePassword from "./AccountChangePassword";
import Loading from "@vue/components/Preloader/Loading";

export default {
  name: "AccountComponent",
  components: {
    AccountOrders,
    AccountEdit,
    AccountChangePassword,
    Loading,
  },
  data() {
    return {
      activeTab: 0,
    }
  },
  computed: {
    userData() {
      const data = this.$store.getters.getUserData;
      return data ? data : null;
    },
    orders() {
      const data = this.$store.getters.getUserOrders;
      return data ? data : [];
    }
  },
  methods: {
    onLogout() {
      this.$store.dispatch("onLogout");
    },
  },
  created() {
    this.$store.dispatch("fetchUserdata");
    this.$store.dispatch("fetchUserOrders");
  }
}
</script>
