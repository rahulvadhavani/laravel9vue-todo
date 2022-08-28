<template>
   <Todolist></Todolist>
</template>
<script>
import { ref, onMounted } from "vue";
import Todolist from './components/Todolist.vue';
export default {
  setup() {
    const data = ref("Rv click nahi karna hai");
    const rvNeclickKiya = () => {
      data.value = "Nahi karna tha yaar";
    };
    return {
      data,
      rvNeclickKiya,
    };
  },
  components: {
    Todolist
  },
};
</script>