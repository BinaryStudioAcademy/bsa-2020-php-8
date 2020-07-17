import requestService from './requestService';

const onError = (error) => Promise.reject(error.response.data);

const applyFilter = ({ id, image, filter }) => {
	return requestService.post(`/api/images/${id}`, {
		src: image,
		filter: filter,
	}).catch(onError);
};

const getFilters = () => {
	return requestService.get(`/api/filters`).catch(onError);
};

export default {
	applyFilter,
	getFilters,
};
