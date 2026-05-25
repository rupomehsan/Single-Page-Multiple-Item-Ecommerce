export const initialState = {
    siteData: {
        name: 'Food Corner',
        logoUrl: null as string | null,
        phone: '01711-123456',
        address: 'ঢাকা, বাংলাদেশ',
        hours: 'সকাল ১০টা – রাত ১০টা · প্রতিদিন খোলা',
    },

    hero: {} as Record<string, any>,
    heroStyle: {} as Record<string, string>,

    activeNav: 'home',
    messengerUrl: 'https://m.me/',
    whatsappUrl: 'https://wa.me/',
    whatsappNumber: '',

    menu: [] as any[],
    categories: [] as { id: string; name: string }[],
    currentCategory: 'all',
    productsLoading: true,

    cart: {} as Record<number, number>,
    cartOpen: false,
    cartBump: false,

    testimonials: [] as any[],
    slCurrent: 0,
    slTouchStartX: 0,
    avatarGradients: [
        'linear-gradient(135deg, #f8bbd0, #e63946)',
        'linear-gradient(135deg, #bbdefb, #1565c0)',
        'linear-gradient(135deg, #c8e6c9, #2e7d32)',
        'linear-gradient(135deg, #ffe0b2, #e65100)',
        'linear-gradient(135deg, #e1bee7, #6a1b9a)',
        'linear-gradient(135deg, #b2dfdb, #00796b)',
    ],

    ofShip: 60,
    orderForm: { name: '', phone: '', address: '', notes: '' } as {
        name: string; phone: string; address: string; notes: string;
    },

    promoCode: '',
    promoLoading: false,
    promoMsg: '',
    promoApplied: false,
    promoDiscount: 0,
    promoCodeApplied: '',

    modalOpen: false,
    orderSuccess: false,
    modal: { name: '', phone: '', address: '', note: '', shipping: 60 } as {
        name: string; phone: string; address: string; note: string; shipping: number;
    },

    orderSubmitting: false,

    toastMsg: '',
    toastVisible: false,

    shippingOptions: [
        { value: 60,  label: 'ঢাকার ভেতরে — ৳ ৬০' },
        { value: 120, label: 'ঢাকার বাইরে — ৳ ১২০' },
    ] as { value: number; label: string }[],

    emojis: ['🍛', '🍕', '🍔', '🍜', '🥗', '🍗', '🥩', '🍲', '🧆', '🍮', '🌮', '🍟'],
};
