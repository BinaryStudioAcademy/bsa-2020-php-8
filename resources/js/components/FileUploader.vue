<template>
<div class="file-uploader">
	<label
		for="file-uploader"
		class="file-uploader__container row"
		@drop.prevent="onDrop"
		@dragover.prevent
	>
		<div v-if="hasPreviews" class="row file-uploader__preview">
			<image-preview
				v-for="imageId in previews"
				:key="imageId"
				:image="previewById[imageId]"
				:id="imageId"
				v-on:delete="onDelete"
				v-on:update="onUpdate"
				v-on:select="onSelect"
			/>
		</div>
		<div v-else class="file-uploader__placeholder">
			<div>DRAG & DROP IMAGES HERE</div>
		</div>
	</label>
	<input
		id="file-uploader"
		type="file"
		class="file-uploader__input"
		v-on:change="onChange"
		multiple
	>
</div>
</template>

<script>
import ImagePreview from './ImagePreview';

export default {
	components: {
		"image-preview": ImagePreview,
	},
	props: {
		previews: Array,
		previewById: Object,
	},
	computed: {
		hasPreviews() {
			return this.previews.length;
		}
	},
	methods: {
		async onChange(e) {
			this.uploadFiles(e.target.files);
		},

		async onDrop(e) {
			this.uploadFiles(e.dataTransfer.files);
		},

		async uploadFiles(files) {
			const data = await this.readFiles(files);

			this.$emit("change", data);
		},

		readFiles(files) {
			return Promise.all([].map.call(files, this.readFile));
		},

		readFile(file) {
			return new Promise((resolve, reject) => {
				const reader = new FileReader();

				reader.addEventListener('error', reject);
				reader.addEventListener('loadend', (e) => {
					resolve(e.target.result);
				});

				reader.readAsDataURL(file);
			});
		},

		onDelete(...data) {
			this.$emit("delete", ...data);
		},
	
		onUpdate(...data) {
			this.$emit("update", ...data);
		},
	
		onSelect(...data) {
			this.$emit("select", ...data);
		}
	}
};
</script>

<style lang="scss" scoped>
.file-uploader {
	overflow: auto;

	&__preview {
		margin: 0;
	}

	&__container {
		width: 100%;
		height: calc(100vh - 77.22px);
		margin: 10px 0;
	}

	&__input {
		display: none;
	}

	&__placeholder {
		height: 100%;
		width: 100%;
		color: #e1e1e1;
		font-size: 3rem;
		font-weight: bold;
		display: flex;
		justify-content: center;
		align-items: center;
		cursor: pointer;
	}
}
</style>