<template>
	<div class="col-md-4" v-on:click.prevent.stop v-on:mouseover="onHover" v-on:mouseleave="onUnhover">
		<div class="card mb-3 image-card" v-on:click="onSelect">
			<div :class="{ 'image-card__check': true, 'image-card__check--enabled': image.selected, 'image-card__check--hover': buttons }">
				<svg class="image-card__check-tick" height="32" width="32" viewBox="0 0 515.556 515.556" xmlns="http://www.w3.org/2000/svg">
					<path d="m0 274.226 176.549 176.886 339.007-338.672-48.67-47.997-290.337 290-128.553-128.552z"/>
				</svg>
			</div>
			<div v-if="image.error" class="image-card__error" :data-error="image.error" :title="image.error"></div>
			<div class="row no-gutters">
				<div class="col-xs-12">
					<img :src="image.src" class="card-img" />
				</div>
			</div>
			<div v-if="image.processing" class="image-card__processing"><span>Processing</span></div>
			<div v-else :class="{ 'image-card__buttons--enabled': buttons, 'image-card__buttons': true }">
				<a href="#" class="btn btn-primary" v-on:click.prevent.stop="onUpdate">Update</a>
				<a href="#"  class="btn btn-outline-warning" v-on:click.prevent.stop="onDelete">Delete</a>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	props: {
		id: String,
		image: Object,
	},
	data() {
		return {
			buttons: false,
		};
	},
	methods: {
		onHover() {
			this.buttons = true;
		},

		onUnhover() {
			this.buttons = false;
		},

		onSelect() {
			if (!this.image.processing) {
				this.$emit("select", this.id);
			}
		},

		onUpdate() {
			this.$emit("update", this.id);
		},

		onDelete() {
			this.$emit("delete", this.id);
		}
	}
}
</script>

<style lang="scss" scoped>
.image-card {
	position: relative;
	overflow: hidden;
	cursor: pointer;

	&__buttons {
		position: absolute;
		height: 60px;
		bottom: -60px;
		width: 100%;
		display: flex;
		justify-content: space-around;
		align-items: center;
		transition-duration: 200ms;
		transition-property: bottom, background-color;
		padding: 5px 0;
		background-color: rgba(255, 255, 255, 0.1);

		&--enabled {
			bottom: 0;
			background-color: rgba(111, 111, 111, 0.7);
		}
	}

	&__processing {
		background-color: rgba(111, 111, 111, 0.7);
		white-space: nowrap;
		text-align: center;
		color: #fff;
		position: absolute;
		bottom: 0;
		width: 100%;

		span:after {
			content: '';
			display: inline-block;
			width: 15px;
			animation: procesiing 3s infinite;
			@keyframes procesiing {
				from { content: '.' }
				20% { content: '..' }
				40% { content: '...' }
				65% { content: '..' }
				80% { content: '.' }
				to { content: '' }
			}
		}
	}

	&__check {
		position: absolute;
		top: -80px;
		height: 0;
		width: 0;
		border-color: rgba(196, 196, 196, 0.7);
		border-width: 40px;
		border-style: solid;
		border-bottom-color: transparent;
		border-right-color: transparent;
		transition-duration: 200ms;
		transition-property: top;

		&-tick {
			fill: #fff;
			position: absolute;
			top: -30px;
			left: -30px;
		}

		&--hover {
			top: 0;
		}

		&--enabled {
			top: 0;
			border-top-color: rgba(111, 111, 111, 0.7);
			border-left-color: rgba(111, 111, 111, 0.7);

			.image-card__check-tick {
				fill: #5ade92;
			}
		}
	}

	&__error {
		position: absolute;
		left: 0;
		top: 0;
		right: 0;
		bottom: 0;
		border: 10px solid rgba(255, 0,0,0.5);
		border-radius: 5px;

		&::after {
			content: attr(data-error);
			position: absolute;
			color: #fff;
			right: 0;
			background-color: rgba(255, 0,0,0.5);
			padding: 0 10px 0 15px;
			max-width: 75%;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}
	}
}
</style>
