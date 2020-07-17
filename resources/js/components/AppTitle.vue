const { template } = require("lodash");

<template>
	<nav class="navbar navbar-light bg-light app-title">
		<div class="col-md-5">
			<a class="navbar-brand" href="#">
				<img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIj4KICA8ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPgogICAgPHBhdGggZmlsbD0iI0ZGQzcxMiIgZD0iTTU5LjkyNyA3Ni44ODFjLjI2Ny42MTYtLjQyNyAxLjIwNS0uOTkuODM4TDI5LjMxIDU4LjM3NmMtMi4yNTktMS4yOTktMS43NDMtMy43NzIgMC00LjUyNmwxNi41ODUtNi4xMjVjLjY1LS4yNCAxLjM3LjA3IDEuNjQ3LjcwM2wxMi4zODYgMjguNDUzeiIvPgogICAgPHBhdGggZmlsbD0iIzAwQURFRSIgZD0iTTg3LjE5MyA3My4yMTRMNjguOTk5IDgxLjA5Yy0xLjE2LjUwMy0yLjU0Ni0uMTI1LTMuMS0xLjQwMkw0Mi40NjUgMjUuNTY1Yy0uNTU1LTEuMjgtLjA2MS0yLjcyNiAxLjEwMS0zLjIzbDE4LjE5My03Ljg3NmMxLjE2LS41MDIgMi41NDguMTI1IDMuMTAxIDEuNDAybDIzLjQzNiA1NC4xMjRjLjU1NCAxLjI4LjA2IDIuNzI2LTEuMTAyIDMuMjMiLz4KICAgIDxwYXRoIGZpbGw9IiNFQzE4NDgiIGQ9Ik0zOS42ODIgMzAuMDM0YzEuMjc5IDIuOSA0LjIxMyA5LjcwNCA1LjM2IDEyLjM2OGExLjEzNyAxLjEzNyAwIDAgMS0uNjQ2IDEuNTE2bC0xMi42NzkgNC43NTdhLjY3OC42NzggMCAwIDEtLjg1OS0uOTA0bDcuNjMzLTE3LjczYS42NS42NSAwIDAgMSAxLjE5MS0uMDA3bS42NjkgNDAuMzE2bC0xMy45MjQtOS4yOTRhMS4wNjYgMS4wNjYgMCAwIDAtMS41NjcuNDZsLTcuOTc2IDE4LjIwNmMtLjQwNC45MjQuNTMgMS44NjQgMS40NTUgMS40NjVsMjEuODg5LTkuNDczYS43OC43OCAwIDAgMCAuMTIzLTEuMzYzIi8+CiAgICA8cGF0aCBmaWxsPSIjQzIyMDM1IiBkPSJNNDAuMzUxIDcwLjM1bC0xMy45MjQtOS4yOTRhMS4wNjYgMS4wNjYgMCAwIDAtMS41NjcuNDZsOS40MDIgMTIuNzggNS45NjYtMi41ODJhLjc4Ljc4IDAgMCAwIC4xMjMtMS4zNjMiLz4KICA8L2c+Cjwvc3ZnPg==" width="30" height="30" class="d-inline-block align-top" alt="" loading="lazy">
				BSA 2020 | PHP | Message queues
			</a>
		</div>
		<select class="form-control col-md-3" v-on:change="onFilter" :value="filter">
			<option v-for="filter in filters" :key="filter">{{ filter }}</option>
		</select>
		<div class="col-md-4 text-right">
			<a href="#" class="btn btn-outline-dark" v-on:click.prevent="onSelectAll">Select All</a>
			<a href="#" :class="{ 'btn': true, 'btn-outline-dark': true, 'disabled': !hasSelected }" v-on:click.prevent="onDelete">Delete</a>
			<a href="#" :class="{ 'btn': true, 'btn-primary': true, 'disabled': !hasSelected }" v-on:click.prevent="onUpdate">Update</a>
			<a href="#" :class="{ 'btn': true, 'btn-dark': true }" v-on:click.prevent="onLogout">Log out</a>
		</div>
	</nav>	
</template>

<script>
export default {
	props: {
		hasSelected: Boolean,
		filters: Array,
		filter: String,
	},
	methods: {
		onDelete() {
			if (this.hasSelected) {
				this.$emit("delete");
			}
		},

		onUpdate() {
			if (this.hasSelected) {
				this.$emit("update");
			}
		},

		onFilter(e) {
			this.$emit("filter", e.target.value);
		},

		onSelectAll() {
			this.$emit("selectAll");
		},

		onLogout() {
			this.$emit("logout");
		},
	}
}
</script>

<style scoped>
.app-title {
	border-bottom: 1px solid #fff;
}
</style>