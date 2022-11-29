<?php

namespace BPT\types;

use stdClass;

/**
 * This object represents the content of a message to be sent as a result of an inline query.
 * @method self setMessage_text(string $value)
 * @method self setParse_mode(string $value)
 * @method self setEntities(array $value)
 * @method self setDisable_web_page_preview(bool $value)
 * @method self setLatitude(float $value)
 * @method self setLongitude(float $value)
 * @method self setHorizontal_accuracy(float $value)
 * @method self setLive_period(int $value)
 * @method self setHeading(int $value)
 * @method self setProximity_alert_radius(int $value)
 * @method self setTitle(string $value)
 * @method self setAddress(string $value)
 * @method self setFoursquare_id(string $value)
 * @method self setFoursquare_type(string $value)
 * @method self setGoogle_place_id(string $value)
 * @method self setGoogle_place_type(string $value)
 * @method self setPhone_number(string $value)
 * @method self setFirst_name(string $value)
 * @method self setLast_name(string $value)
 * @method self setVcard(string $value)
 * @method self setDescription(string $value)
 * @method self setPayload(string $value)
 * @method self setProvider_token(string $value)
 * @method self setCurrency(string $value)
 * @method self setPrices(array $value)
 * @method self setMax_tip_amount(int $value)
 * @method self setSuggested_tip_amounts(array $value)
 * @method self setProvider_data(string $value)
 * @method self setPhoto_url(string $value)
 * @method self setPhoto_size(int $value)
 * @method self setPhoto_width(int $value)
 * @method self setPhoto_height(int $value)
 * @method self setNeed_name(bool $value)
 * @method self setNeed_phone_number(bool $value)
 * @method self setNeed_email(bool $value)
 * @method self setNeed_shipping_address(bool $value)
 * @method self setSend_phone_number_to_provider(bool $value)
 * @method self setSend_email_to_provider(bool $value)
 * @method self setIs_flexible(bool $value)
 */
class inputMessageContent extends types {
    /** Keep all of properties which has sub properties */
    private const subs = ['array' => ['entities' => 'BPT\types\messageEntity', 'prices' => 'BPT\types\labeledPrice']];

    /** `text` only. Text of the message to be sent, 1-4096 characters */
    public null|string $message_text = null;

    /** `text` only. Optional. Mode for parsing entities in the message text. See formatting options for more details. */
    public null|string $parse_mode = null;

    /**
     * `text` only. Optional. List of special entities that appear in message text, which can be specified instead of parse_mode
     * @var messageEntity[]
     */
    public null|array $entities = null;

    /** `text` only. Optional. Disables link previews for links in the sent message */
    public null|bool $disable_web_page_preview = null;

    /** `location` and `venue` only. Latitude in degrees */
    public null|float $latitude = null;

    /** `location` and `venue` only. Longitude in degrees */
    public null|float $longitude = null;

    /** `location` only. Optional. The radius of uncertainty for the location, measured in meters; 0-1500 */
    public null|float $horizontal_accuracy = null;

    /** `location` only. Optional. Period in seconds for which the location can be updated, should be between 60 and 86400. */
    public null|int $live_period = null;

    /** `location` only. Optional. For live locations, a direction in which the user is moving, in degrees. Must be between 1 and 360 if specified. */
    public null|int $heading = null;

    /**
     * `location` only. Optional. For live locations, a maximum distance for proximity alerts about approaching another chat member,
     * in meters. Must be between 1 and 100000 if specified.
     */
    public null|int $proximity_alert_radius = null;

    /** `venue` and `invoice` only. name of the venue or product */
    public null|string $title = null;

    /** `venue` only. Address of the venue */
    public null|string $address = null;

    /** `venue` only. Optional. Foursquare identifier of the venue, if known */
    public null|string $foursquare_id = null;

    /**
     * `venue` only. Optional. Foursquare type of the venue, if known. (For example, “arts_entertainment/default”,
     * “arts_entertainment/aquarium” or “food/icecream”.)
     */
    public null|string $foursquare_type = null;

    /** `venue` only. Optional. Google Places identifier of the venue */
    public null|string $google_place_id = null;

    /** `venue` only. Optional. Google Places type of the venue. (See supported types.) */
    public null|string $google_place_type = null;

    /** `contact` only. Contact's phone number */
    public null|string $phone_number = null;

    /** `contact` only. Contact's first name */
    public null|string $first_name = null;

    /** `contact` only. Optional. Contact's last name */
    public null|string $last_name = null;

    /** `contact` only. Optional. Additional data about the contact in the form of a vCard, 0-2048 bytes */
    public null|string $vcard = null;

    /** `invoice` only. Product description, 1-255 characters */
    public null|string $description = null;

    /**
     * `invoice` only. Bot-defined invoice payload, 1-128 bytes. This will not be displayed to the user, use for your internal
     * processes.
     */
    public null|string $payload = null;

    /** `invoice` only. Payment provider token, obtained via BotFather */
    public null|string $provider_token = null;

    /** `invoice` only. Three-letter ISO 4217 currency code, see more on currencies */
    public null|string $currency = null;

    /**
     * `invoice` only. Price breakdown, a JSON-serialized list of components (e.g. product price, tax, discount, delivery cost,
     * delivery tax, bonus, etc.)
     * @var labeledPrice[]
     */
    public null|array $prices = null;

    /**
     * `invoice` only. Optional. The maximum accepted amount for tips in the smallest units of the currency (integer, not
     * float/double). For example, for a maximum tip of US$ 1.45 pass max_tip_amount = 145. See the exp parameter in
     * currencies.json, it shows the number of digits past the decimal point for each currency (2 for the majority of
     * currencies). Defaults to 0
     */
    public null|int $max_tip_amount = null;

    /**
     * `invoice` only. Optional. A JSON-serialized array of suggested amounts of tip in the smallest units of the currency (integer,
     * not float/double). At most 4 suggested tip amounts can be specified. The suggested tip amounts must be
     * positive, passed in a strictly increased order and must not exceed max_tip_amount.
     * @var int[]
     */
    public null|array $suggested_tip_amounts = null;

    /**
     * `invoice` only. Optional. A JSON-serialized object for data about the invoice, which will be shared with the payment provider.
     * A detailed description of the required fields should be provided by the payment provider.
     */
    public null|string $provider_data = null;

    /**
     * `invoice` only. Optional. URL of the product photo for the invoice. Can be a photo of the goods or a marketing image for a
     * service.
     */
    public null|string $photo_url = null;

    /** `invoice` only. Optional. Photo size in bytes */
    public null|int $photo_size = null;

    /** `invoice` only. Optional. Photo width */
    public null|int $photo_width = null;

    /** `invoice` only. Optional. Photo height */
    public null|int $photo_height = null;

    /** `invoice` only. Optional. Pass True, if you require the user's full name to complete the order */
    public null|bool $need_name = null;

    /** `invoice` only. Optional. Pass True, if you require the user's phone number to complete the order */
    public null|bool $need_phone_number = null;

    /** `invoice` only. Optional. Pass True, if you require the user's email address to complete the order */
    public null|bool $need_email = null;

    /** `invoice` only. Optional. Pass True, if you require the user's shipping address to complete the order */
    public null|bool $need_shipping_address = null;

    /** `invoice` only. Optional. Pass True, if the user's phone number should be sent to provider */
    public null|bool $send_phone_number_to_provider = null;

    /** `invoice` only. Optional. Pass True, if the user's email address should be sent to provider */
    public null|bool $send_email_to_provider = null;

    /** `invoice` only. Optional. Pass True, if the final price depends on the shipping method */
    public null|bool $is_flexible = null;


    public function __construct(stdClass|null $object = null) {
        if ($object != null) {
            parent::__construct($object, self::subs);
        }
    }
}