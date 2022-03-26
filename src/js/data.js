var image_url = "https://d3ie7ogstrup9y.cloudfront.net/wp-content/uploads/2021/01/19085808/PNG-RGB-72-DPI-Bahrain_GulfAir_Logo_2021_ETG_POS_Standard_RGB.png";

export const races = [
  {
    id: 1,
    name: 'Bahrain GP',
    image: 'https://f1-weekly.com/images/Screenshot-2022-03-17-at-19-34-2.png',
    date: '2019-01-01',
    status: 'done'
  },
  {
    id: 2,
    name: 'Saudi Arabia GP',
    image: 'https://scontent.fkun1-1.fna.fbcdn.net/v/t39.30808-6/277368253_106771571981473_844964204046739531_n.jpg?_nc_cat=103&ccb=1-5&_nc_sid=09cbfe&_nc_ohc=I-pOc-242PcAX_8dt67&tn=24HC3XIzjvAYxgqU&_nc_ht=scontent.fkun1-1.fna&oh=00_AT-WVssY4Q5HDsl2IUg1kflRFHvGmZsB6EQYDI_1iXt7ZQ&oe=6243D313',
    date: '2022-03-27',
    status: 'next'
  },
  {
    id: 3,
    name: 'Australian GP',
    image: 'https://eim.solutions/wp-content/uploads/2022/03/F1-weekly-Logo-2.png',
    date: '2022-04-10',
    status: 'future'
  },
  {
    id: 4,
    title: 'Drinks',
    image: image_url,
    date: '2022-04-10',
    status: 'future'
  },
];

export const items = [
  {
    id: 1,
    name: 'Diablo',
    subtitle: 'Spicy chorizo with hot jalapeno peppers',
    description:
      'Spicy chorizo , hot jalapeno peppers , barbecue sauce , mithballs , tomatoes , sweet peppers , red onions , mozzarella , tomato sauce',
    categoryId: 2,
    image: 'images/pizza/diablo.png',
    prices: [
      {
        title: '20 cm',
        value: 6.5,
      },
      {
        title: '30 cm',
        value: 10.25,
      },
      {
        title: '40 cm',
        value: 13.0,
      },
    ],
  },
  {
    id: 2,
    title: 'Margherita',
    subtitle: 'Mozzarella & tomatoes',
    description:
      'Enlarged portion of mozzarella, tomatoes, Italian herbs, tomato sauce',
    categoryId: 2,
    image: 'images/pizza/margherita.png',
    prices: [
      {
        title: '20 cm',
        value: 4.45,
      },
      {
        title: '30 cm',
        value: 6.17,
      },
      {
        title: '40 cm',
        value: 10.21,
      },
    ],
  },
  {
    id: 3,
    title: 'Mix',
    subtitle: 'Bacon, chicken, ham',
    description:
      'Bacon, chicken, ham, blue cheese, cheddar and parmesan cheeses, pesto sauce, cubes of brynza, tomatoes, red onions, mozzarella, alfredo sauce, garlic, Italian herbs',
    categoryId: 2,
    image: 'images/pizza/mix.png',
    prices: [
      {
        title: '20 cm',
        value: 6.59,
      },
      {
        title: '30 cm',
        value: 9.99,
      },
      {
        title: '40 cm',
        value: 15.0,
      },
    ],
  },
  {
    id: 4,
    title: 'Pepperoni',
    subtitle: 'Spicy pepperoni with extra mozzarella',
    description: 'Spicy pepperoni, extra mozzarella, tomatoes, tomato sauce',
    categoryId: 2,
    image: 'images/pizza/pepperoni.png',
    prices: [
      {
        title: '20 cm',
        value: 6.25,
      },
      {
        title: '30 cm',
        value: 10.5,
      },
      {
        title: '40 cm',
        value: 13.0,
      },
    ],
  },
  {
    id: 5,
    title: 'Pepsi',
    subtitle: 'A great taste of legendary classics',
    categoryId: 4,
    image: 'images/drinks/pepsi.png',
    price: 1.25,
  },
  {
    id: 6,
    title: 'Mirinda',
    subtitle: 'Intensely fruity taste',
    categoryId: 4,
    image: 'images/drinks/mirinda.png',
    price: 1.25,
  },
  {
    id: 7,
    title: '7UP',
    subtitle: "7 UP. It's An Up Thing",
    categoryId: 4,
    image: 'images/drinks/7up.png',
    price: 1.25,
  },
  {
    id: 8,
    title: 'Aqua Minerale',
    subtitle: 'Sparkling water',
    categoryId: 4,
    image: 'images/drinks/aqua.png',
    price: 1.25,
  },
  {
    id: 9,
    title: 'Apple Pie',
    subtitle: '100% American-grown apples',
    categoryId: 3,
    image: 'images/desserts/apple-pie.png',
    price: 1.99,
    description:
      'Apple Pie recipe features 100% American-grown apples, and a lattice crust baked to perfection and topped with sprinkled sugar',
  },
  {
    id: 10,
    title: 'Chocolate Donut',
    subtitle: 'Chocolate and cream filling',
    categoryId: 3,
    image: 'images/desserts/chocolate-donut.png',
    price: 0.99,
    description:
      'Tender donut with chocolate and cream filling, covered with milk and chocolate frosting.',
  },
  {
    id: 11,
    title: 'Cinnamon Bun',
    subtitle: 'Cream cheese icing',
    categoryId: 3,
    image: 'images/desserts/cinnamon-bun.png',
    price: 1.99,
    description:
      'Cinnamon bun is served warm and loaded with cinnamon layered between buttery, flaky pastry dough that is drizzled with a delicious cream cheese icing',
  },
  {
    id: 12,
    title: 'Raspberry Pie',
    subtitle: 'Hot puff pie',
    categoryId: 3,
    image: 'images/desserts/raspberry-pie.png',
    price: 1.99,
    description:
      'Hot puff pie with crispy crust and blueberry, raspberry, blackberry, strawberry and currant filling',
  },
  {
    id: 13,
    title: 'Vanilla Donut',
    subtitle: 'Vanilla cream filling',
    categoryId: 3,
    image: 'images/desserts/vanilla-donut.png',
    price: 0.99,
    description:
      'Aromatic donut with a delicate vanilla cream filling, covered in white and chocolate frosting.',
  },
  {
    id: 14,
    title: 'Big Tasty',
    subtitle: '100% fresh beef burger',
    categoryId: 1,
    image: 'images/burgers/big-tasty.png',
    price: 3.99,
    description:
      '100% fresh beef burger patties that are hot, deliciously juicy and cooked when you order. Our beef patties are seasoned with just a pinch of salt and pepper, sizzled on a flat iron grill, then topped with slivered onions, tangy pickles and two slices of melty cheese on a sesame seed bun. It contains no artificial flavors, preservatives or added colors from artificial sources',
  },
  {
    id: 15,
    title: 'Big Mac Bacon',
    subtitle: 'Classic with bacon',
    categoryId: 1,
    image: 'images/burgers/bigmac-bacon.png',
    price: 2.99,
    description:
      'Mouthwatering perfection starts with two 100% pure beef patties and Big Mac sauce sandwiched between a sesame seed bun. It’s topped off with pickles, crisp shredded lettuce, finely chopped onion and American cheese for a 100% beef burger with a taste like no other. It contains no artificial flavors, preservatives or added colors from artificial sources.',
  },
  {
    id: 16,
    title: 'Big Mac',
    subtitle: 'Mouthwatering perfection',
    categoryId: 1,
    image: 'images/burgers/bigmac.png',
    price: 2.49,
    description:
      'Mouthwatering perfection starts with two 100% pure beef patties and Big Mac sauce sandwiched between a sesame seed bun. It’s topped off with pickles, crisp shredded lettuce, finely chopped onion and American cheese for a 100% beef burger with a taste like no other. It contains no artificial flavors, preservatives or added colors from artificial sources.',
  },
  {
    id: 17,
    title: 'Cheeseburger',
    subtitle: 'Our simple, classic cheeseburger',
    categoryId: 1,
    image: 'images/burgers/cheeseburger.png',
    price: 0.99,
    description:
      'Our simple, classic cheeseburger begins with a 100% pure beef burger seasoned with just a pinch of salt and pepper. The Cheeseburger is topped with a tangy pickle, chopped onions, ketchup, mustard, and a slice of melty American cheese. It contains no artificial flavors, preservatives or added colors from artificial sources',
  },
  {
    id: 18,
    title: 'Chicken Premier',
    subtitle: 'Southern style fried chicken',
    categoryId: 1,
    image: 'images/burgers/chicken-premier.png',
    price: 2.15,
    description:
      "Southern style fried chicken sandwich that's crispy, juicy and tender perfection. It’s topped with crinkle-cut pickles and served on a toasted, buttered potato roll",
  },
  {
    id: 19,
    title: 'Chickenburger',
    subtitle: 'Classic for a reason',
    categoryId: 1,
    image: 'images/burgers/chickenburger.png',
    price: 2.15,
    description:
      'It’s a classic for a reason. Savor the satisfying crunch of our juicy chicken patty, topped with shredded lettuce and just the right amount of creamy mayonnaise, all served on a perfectly toasted bun.',
  },
  {
    id: 20,
    title: 'Double Cheeseburger',
    subtitle: 'Double the classic',
    categoryId: 1,
    image: 'images/burgers/double-cheeseburger.png',
    price: 1.99,
    description:
      "The Double Cheeseburger features two 100% pure beef burger patties seasoned with just a pinch of salt and pepper. It's topped with tangy pickles, chopped onions, ketchup, mustard and two slices of melty American cheese. There are 450 calories in a Double Cheeseburger. It contains no artificial flavors, preservatives or added colors from artificial sources",
  },
  {
    id: 21,
    title: 'Filet-O-Fish',
    subtitle: 'Wild-caught Filet-O-Fish',
    categoryId: 1,
    image: 'images/burgers/filet-o-fish.png',
    price: 2.45,
    description:
      'Dive into our wild-caught Filet-O-Fish! This fish sandwich has fish sourced from sustainably managed fisheries, on melty American cheese and topped with creamy tartar sauce, all served on a soft, steamed bun',
  },
];
