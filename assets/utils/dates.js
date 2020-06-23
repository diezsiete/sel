import moment from 'moment';

const formatDateTime = function(date) {
  if (!date) return null;

  return moment(date).format('YYYY-MM-DD'); // 'DD/MM/YYYY'
};

export { formatDateTime };
