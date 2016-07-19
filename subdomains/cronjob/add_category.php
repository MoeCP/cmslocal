<?php
require_once 'pre_cron.php';
require_once  CMS_INC_ROOT . DS . 'Category.class.php';
$cats = array(
	'2' => array(
		'Birds',
		'Cats',
		'Dogs',
		'Exotic Animals',
		'Farm animals',
		'Fish',
		'Horses',
		'Insects',
		'Mammals',
		'Non-traditional Pets',
		'Pet Health',
		'Reptiles and Amphibians',
		'Zoo Animals',
	),
	'1' => array(
		'Animation, Design, and Illustration',
		'Audio and Music',
		'Beauty Pageants',
		'Body Art',
		'Books',
		'Celebrities',
		'Commercials and Advertising',
		'Film, TV, and Video',
		'Fine Arts',
		'Graphic Design',
		'Literature',
		'Magic',
		'People',
		'Performing Arts',
		'Photography',
		'Visual Arts',
	),
	'3' => array(
		'Aircraft',
		'Auto Racing',
		'Cars',
		'Clubs',
		'Custom Auto',
		'Driving and Safety',
		'Events and Shows',
		'Green Vehicles',
		'Large Vehicles',
		'Mobile Homes',
		'Motorcycles',
		'Off Road Vehicles',
		'Parts and Accessories',
		'Repair',
		'Tractors',
		'Watercraft',
	),
	'4' => array(
		'Business Services',
		'Careers and Job Advancement',
		'Consumer Information',
		'Financial Services',
		'Green Office',
		'Human Resources',
		'Investment',
		'Law',
		'Major Companies',
		'Management',
		'Marketing and Promotion',
		'Office Supplies and Equipment',
		'Personal Finance',
		'Real Estate',
		'Small Businesses',
		'Startups',
		'Security',
		'Logistics',
		'Insurance',
		'Sales',
		'Work Life',
	),
	'33' => array(
		'Building Computers',
		'Computer Science',
		'Digital Imaging',
		'Hardware',
		'Internet',
		'Operating Systems',
		'Printers and Scanners',
		'Programming',
		'Purchasing Computers',
		'Software',
		'Troubleshooting and Repair',
		'Virus and Spyware Protection',
		'Technical Writing',
	),
	'9' => array(
		'Analysis and Opinion',
		'Astrology',
		'Charities',
		'Children',
		'Dating',
		'Education',
		'Ethics',
		'Family',
		'Fashion',
		'Food',
		'History',
		'Holidays and Celebrations',
		'LGBT',
		'Marriage',
		'Organizations',
		'Parenting',
		'People and Societies',
		'Personal Development',
		'Politics',
		'Pop Culture',
		'Reference',
		'Relationships',
		'Religion and Spirituality',
		'Rural',
		'Seniors',
		'Student Life',
		'Teens/20\'s',
		'Urban',
		'Wedding',
	),
	'7' => array(
		'Cameras',
		'Car Audio',
		'Consumer Electronics',
		'Phones and PDAs',
	),
	'13' => array(
		'Addiction',
		'Alternative Medicine',
		'Conditions, Diseases, and Treatment',
		'Dental Health',
		'Dieting and Nutrition',
		'Drugs and Medicine',
		'Exercise Equipment',
		'Geriatrics',
		'Mental Health',
		'Personal Care and Beauty',
		'Personal Fitness',
		'Pregnancy',
		'Reproductive Health',
		'Self Help',
		'Sexuality',
		'Weightlifting',
		'Weight Loss',
		'Western Medicine',
		'Women\'s Health',
	),
	'12' => array(
		'Antiques and Collectibles',
		'Backyard and Outdoor Games',
		'Billiards',
		'Board Games',
		'Card Games',
		'Crafts',
		'Models',
		'Party Games',
		'Puzzles',
		'RC Vehicles',
		'Shopping',
		'Toys',
		'Video Games',
		'Woodworking',
	),
	'15' => array(
		'Appliances',
		'Bathroom',
		'Bedroom',
		'Cleaning',
		'Closets',
		'Emergency Preparation',
		'Flowers',
		'Gardening',
		'Green Living',
		'Home Improvement',
		'Interior Design',
		'Kitchen',
		'Living Room',
		'New Homes',
		'Outdoors',
		'Tools',
	),
	'24' => array(
		'Civil Suit',
		'Contract Law',
		'Criminal Law',
		'Divorce and Family',
		'Immigration Law',
		'Other Law',
		'Tax Law',
	),
	'38' => array(
		'Building and Construction',
		'Chemistry and Physics',
		'Electrical',
		'Engineering and Mechanics',
		'Environment',
		'Experiments',
		'Facts',
		'Heavy Industry',
		'Math',
		'Natural Science and Weather',
		'Other Science',
	),
	'17' => array(
		'Coaching',
		'Individual Sports',
		'Martial Arts',
		'Motor Sports',
		'Multi-Sports',
		'Outdoors and Camping',
		'Spectator Sports',
		'Team Sports',
		'Water Sports',
		'Winter Sports',
	),
	'18' => array(
		'Beach and Resort',
		'Budget',
		'Cruises',
		'Family',
		'Hotels and Lodging',
		'Outdoors and Adventure',
		'Planning Your Trip',
		'Romance',
		'Transportation',
	)
);
foreach ($cats as $pid => $children) {
    foreach ($children as $cat)
    {
        $p['category'] = $cat;
        $p['parent_id'] = $pid;

        $res = Category::store($p);
        if (!$res) {
            echo $cat . ": insert failed! \n";
            continue;
        }
    }
}
exit();
$category = "Animals,Automotive,Business,Careers,Education,Electronics,Entertainment and Arts,Family and Relationships,Finance,Food and Beverages,Gaming,Health and Fitness,Hobbies,Home and Garden,Real Estate,Sports and Recreation,Travell";
$cats = explode(",", $category);
$parent_id = '';
if (empty($parent_id)) {
    $parent_id = 0;
}
foreach ($cats as $cat)
{
    $p['category'] = $cat;
    $p['parent_id'] = $parent_id;
    $res = Category::store($p);
    if (!$res) {
        echo $cat . ": insert failed! <br />";
        continue;
    }
}
echo "succeed";
?>