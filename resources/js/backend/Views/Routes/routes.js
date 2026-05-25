//app layout
import Layout from "../Layouts/Layout.vue";
//Dashboard
import Dashboard from "../Management/Dashboard/Dashboard.vue";
//SettingsRoutes
import SettingsRoutes from "../Management/Settings/setup/routes.js";
//UserRoutes
import UserRoutes from "../Management/UserManagement/User/setup/routes.js";
import UserRoleRoutes from "../Management/UserManagement/Role/setup/routes.js";
//routesimport CustomerRoutes from '../Management/CustomerManagement/Customer/setup/routes.js';
import ProductRoutes from '../Management/ProductManagement/Product/setup/routes.js';
import BannerRoutes from '../Management/CMSManagement/Banner/setup/routes.js';
import TestimonialRoutes from '../Management/CMSManagement/Testimonial/setup/routes.js';
import HowItWorksStepRoutes from '../Management/CMSManagement/HowItWorksStep/setup/routes.js';
import BenefitCardRoutes from '../Management/CMSManagement/BenefitCard/setup/routes.js';
import HeroSectionRoutes from '../Management/CMSManagement/HeroSection/setup/routes.js';
import PageSectionRoutes from '../Management/CMSManagement/PageSection/setup/routes.js';
import DeliveryAreaRoutes from '../Management/DeliveryManagement/DeliveryArea/setup/routes.js';
import PromoCodeRoutes from '../Management/PromoCodeManagement/PromoCode/setup/routes.js';
import FAQRoutes from '../Management/FAQManagement/FAQ/setup/routes.js';
import ReviewRoutes from '../Management/ReviewManagement/Review/setup/routes.js';
import OrderRoutes from '../Management/OrderManagement/Order/setup/routes.js';
import ProductCategoryRoutes from '../Management/ProductManagement/ProductCategory/setup/routes.js';


const routes = {
  path: "/",
  component: Layout,
  children: [
    {
      path: "dashboard",
      component: Dashboard,
      name: "adminDashboard",
    },
    //management routes        CustomerRoutes,
        ProductRoutes,
        BannerRoutes,
        TestimonialRoutes,
        HowItWorksStepRoutes,
        BenefitCardRoutes,
        HeroSectionRoutes,
        PageSectionRoutes,
        DeliveryAreaRoutes,
        PromoCodeRoutes,
        FAQRoutes,
        ReviewRoutes,
        OrderRoutes,
        ProductCategoryRoutes,


    //user routes
    UserRoutes,
    UserRoleRoutes,
    //settings
    SettingsRoutes,
  ],
};

export default routes;
