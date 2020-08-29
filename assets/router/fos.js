const routerConfig = require('../bundles/fosjsrouting/js/fos_js_routes.json');
import router from '../bundles/fosjsrouting/js/router';

router.setRoutingData(routerConfig);

export default router;