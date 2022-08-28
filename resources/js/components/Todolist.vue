<template>
  <section class="bg-bgcolor">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col col-lg-10 col-xl-8">
          <div class="card rounded-3">
            <div class="card-body p-4">
              <h4 class="text-center my-3 pb-3">
                <span class="badge bg-dark px-3">ToDo</span>
              </h4>
              <form class="row g-3 justify-content-center align-items-center mb-4 pb-2" @submit="addToDo">
                <div class="col-lg-10 col-sm-7">
                  <div class="form-outline">
                    <input type="text" required v-model="form.title" class="form-control" id="title_input" placeholder="Enter a task here"/>
                  </div>
                </div>
                <div class="col-lg-2 col-sm-5">
                  <button type="submit"  :disabled="isDisabled" class="btn btn-primary">{{form.id != '' ? 'update' : 'Save'}}</button>
                </div>
              </form>
              <table class="table mb-4">
                <thead>
                  <tr>
                    <th scope="col">No.</th>
                    <th scope="col">Todo item</th>
                    <th scope="col">Status</th>
                    <th scope="col">Time</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(todo, index) in todos" :key="todo.id">
                    <th scope="row">{{index+1}}</th>
                    <td>{{todo.title}}</td>
                    <td><button v-on:click="updateStatus(todo.id)" class="btn btn-sm btn-warning" :class="{'btn-dark text-danger' : todo.status}">{{todo.status ? 'Complate' : 'Pending'}}</button></td>
                    <td>{{todo.created_at}}</td>
                    <td>
                      <button class="btn btn-success btn-sm mx-1" v-on:click="editTodo(todo.id)"> Edit</button>
                      <button class="btn btn-danger btn-sm mx-1" :disabled="isDisabledDelete" v-on:click="deleteTodo(todo.id)"> Delete</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<script>
import axios from "axios";
import { ref, onMounted } from "vue";
import { useToast } from "vue-toastification";

export default {
  setup() {
    const toast = useToast();
    let isDisabled = ref(false);
    let isDisabledDelete = ref(false);
    const baseUrl = document.getElementById('apibaseurl').value;
    let form = ref({
      id: "",
      title: "",
    });
    let todos = ref([]);
    const loadTodos = async()=>{
      await axios.get(`${baseUrl}/todo`)
      .then((response) => {
        console.log(response.data)
        if(response.data.status){
          todos.value = response.data.data;
        }else{
          toast.error(response.data.message);
        }
      })
      .catch((error) => {
        toast.error(error.message);
      })
    }

    const resetForm = ()=>{
      form.value.title= '';
      form.value.id= '';
    };
    const addToDo = async () => {
      isDisabled.value = true
      await axios
        .post(`${baseUrl}/todo`, form.value)
        .then((response) => {
          console.log(response.data)
          if(response.data.status){
            toast.success(response.data.message);
            resetForm();
            loadTodos();
          }else{
            toast.error(response.data.message);
          }
        })
        .catch((error) => {
          toast.error(error.message);
        })
        .finally(()=>{
        isDisabled.value = false;
      });
    };

    const editTodo = async (id) => {
      await axios.get(`${baseUrl}/todo/${id}`)
        .then((response) => {
          if(response.data.status){
            form.value.id = response.data.data.id;
            form.value.title = response.data.data.title;
            document.getElementById('title_input').focus();
          }else{
            toast.error(response.data.message);
          }
        })
        .catch((error) => {
          toast.error(error.message);
        });
    };

    const deleteTodo = async (id) => {
      isDisabledDelete.value = true;
      await axios.delete(`${baseUrl}/todo/${id}`)
      .then((response) => {
          if(response.data.status){
            toast.success(response.data.message);
            loadTodos();
          }else{
            toast.error(response.data.message);
          }
        })
        .catch((error) => {
          toast.error(error.message);
        })
        .finally(()=>{
          isDisabledDelete.value = false;
        });
    };

    const updateStatus = async (id) => {
      await axios.post(`${baseUrl}/todo/update-status`,{'id':id})
        .then((response) => {
          if(response.data.status){
            toast.success(response.data.message);
            loadTodos();
          }else{
            toast.error(response.data.message);
          }
        })
        .catch((error) => {
          toast.error(error.message);
        });
    };

    onMounted(loadTodos);
    return { form, addToDo,loadTodos,deleteTodo,editTodo,updateStatus,isDisabledDelete,isDisabled,todos};
  },
};
</script>
<style scoped>
tbody {
    display:block;
    height:400px;
    overflow:auto;
}
thead, tbody tr {
    display:table;
    width:100%;
    table-layout:fixed;
}
thead {
    width: calc( 100% - 1em )
}
table {
    width:100%;
}
.bg-bgcolor{
  background-color: #eee
}
</style>