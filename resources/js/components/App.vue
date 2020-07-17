<template>
	<div v-if="authenticated" class="container">
		<app-title
			:filters="filters"
			:filter="filter"
			:hasSelected="hasSelected"
			v-on:delete="onDeleteSelected"
			v-on:update="onUpdateSelected"
			v-on:filter="onFilter"
			v-on:selectAll="onSelectAll"
			v-on:logout="onLogout"
		/>
		<file-uploader
			:previewById="imageById"
			:previews="images"
			v-on:delete="onDelete"
			v-on:update="onUpdate"
			v-on:select="onSelect"
			v-on:change="onFile"
		/>
	</div>
	<auth-modal
		v-else
		:show="isNotAuthenticated"
		v-on:hide="onAuth"
	/>
</template>

<script>
import { v4 as uuid } from 'uuid';
import imageService from '../services/imageService';
import echoService from '../services/echoService';
import AppTitle from './AppTitle';
import FileUploader from './FileUploader';
import ImagePreview from './ImagePreview';
import AuthModal from './auth/Modal';
import authService from '../services/authService';

export default {
	components: {
		"app-title": AppTitle,
		"file-uploader": FileUploader,
		"image-preview": ImagePreview,
		"auth-modal": AuthModal,
	},
	data() {
		return {
			imageById: {},
			images: [],
			filters: ["loading..."],
			filter: "loading...",
			authenticated: false,
		};
	},
	computed: {
		hasImages() {
			return this.images.length;
		},
		hasSelected() {
			return Boolean(this.getSelected().length);
		},
		isNotAuthenticated() {
            return !this.authenticated;
        }
	},
	methods: {
		onFile(files) {
			files.forEach((url) => {
				const id = uuid();

				this.imageById[id] = {
					src: url,
					processing: false,
					selected: false,
					error: '',
				};
				this.images.push(id);
			});
		},

		getSelected() {
			return this.images.filter(
				imageId => this.imageById[imageId].selected && !this.imageById[imageId].processing
			);
		},

		onDeleteSelected() {
			const selected = this.getSelected();
			this.images = this.images.filter(
				imageId => !selected.includes(imageId)
			);
			selected.forEach(imageId => {
				Vue.delete(this.imageById, imageId);
			});
		},

		updateImageById(imageId, value) {
			Vue.set(this.imageById, imageId, {
				 ...this.imageById[imageId],
				 ...value,
			});
			this.images = [...this.images];
		},

		setProcessing(imageId, value) {
			this.updateImageById(imageId, { processing: value });
		},

		onUpdateSelected() {
			this.getSelected().forEach(this.updateImage);
		},

		onDelete(imageId) {
			Vue.delete(this.imageById, imageId);
			this.images = this.images.filter(id => id !== imageId);
		},

		onUpdate(imageId) {
			this.updateImage(imageId);
		},

		onSelect(imageId) {
			this.updateImageById(imageId, { selected: !this.imageById[imageId].selected });
		},

		onSelectAll() {
			const images = this.images.reduce((result, imageId) => {
				return {
					...result,
					[imageId]: {
						...this.imageById[imageId],
						selected: true,
					},
				};
			}, {});

			this.imageById = images;
		},

		onFilter(filter) {
			this.filter = filter;
		},

		onLogout() {
			authService.logout();
			this.authenticated = false;
		},

		updateImage(imageId) {
			this.updateImageById(imageId, {
				processing: true,
				error: '',
			});
			// request
			imageService.applyFilter({
				id: imageId,
				image: this.imageById[imageId].src,
				filter: this.filter,
			}).then((response) => {
				this.updateImageById(response.data.id, {
					src: response.data.src,
					processing: false,
					selected: false,
				});
			}).catch((error) => {
				console.error(error);
				this.updateImageById(imageId, {
					processing: false,
					error: error.message,
				});
			});
		},

		onAuth() {
            const user = authService.getUser();
			this.authenticated = true;

			imageService.getFilters().then(response => {
				this.filters = response.data;
				this.filter = response.data[0];
			}).catch(error => {
				console.error(error);
			});

			const Echo = echoService.getEchoInstance(user);

			// !! add subscriptions here !!
		}
	},
    mounted() {
        try {
			this.onAuth();
        } catch (e) {
            this.authenticated = false;
        }
    }
}
</script>
