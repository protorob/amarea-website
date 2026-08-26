The overall look of the website looks great, but as the project is right now, is too complicated. We're going to start fixing that now in V2
First, lets reduce the nuber of blueprints:

1) Homepage/Site blueprint:
  Hero:
    - Eyebrow 
    - Title
    - Description (Rich text)
    - Structure: Buttons
  Blocks:
    - Hero Block (if nothing is set, it can behave as spacer)
      - Set: Background (color/full image/video - If image, select the gradient overlay color)
      - Set: Height (normal/ half / full height)
      - Set: Text Color (mode "options" with a default color palette)
      - Icon
      - Eyebrow
      - Title
      - Description (Rich text)
      - Structure: Buttons (with possibility of define if the button is primary or secondary/ghost)
    - Slider Block
      - Structure: Images + Alt text
    - Locations Block: A grid of the Locations (2 columns on desktop, 1 on mobile), Main location image as background, Title at the bottom, on hover image zooms a bit and overlay apears revealing the description and button to go to the location (on mobile the description is always visible)
    - Elfsignt block for instagram
  General information:
    - Contact information, Address, structure: social links with icon, ecc
    - 

2) Locations Archive blueprint: This is just a container for the locations, the only text fields we need are:
  - Sub Title
  - Description (Rich text)
  - Header image
  - Subpages field (for the single locations)

3) Single "LOCATION PAGE" blueprint: These are the homes that are going to contain rooms or full appartments, we will need the following fields:
  - Hero image (to be used also in the Locations Block)
  - Subtitle
  - Description (Rich text)
  - Short Description
  - Address
  - Images for slider
  - Eyebrow for Space Highlights
  - Title for Space Highlights
  - Description for Space Highlights (Rich text)
  - Structure with fields (image, title, description) for the "Common Spaces Hightlights" for the location. The output will be a grid with each highlight (3 columns on desktop, 1 on mobile), Highlight image as background, Title at the bottom, on hover image zooms a bit and overlay apears revealing the description (on mobile the description is always visible)
  - Probably in a different "Location Common Gallery and amenities" panel/tab:
    - Eyebrow for "Common gallery"
    - Title for "Common gallery"
    - Description for "Common gallery" (Rich text)
    - Files for "Common gallery" (these images are common )
  	- Eyebrow for Location Amenities
  	- Title for Location Amenities
  	- Description for Location Amenities (Rich text)
  	- Structure field for the Location Amenities (icon, text)
  - Subpages field (for the single rooms/appartments)

4) "Single room/apartment" blueprint (bookable unit):
  - Main image (to be used also in the rooms listings inside location)
  - Room title
  - Room Subtitle
  - Room Description (Rich text)
  - Room features (Right now we're using a "tag field but i would like centralize all the type of features we will use around the whole website, maybe in the site blueprint we can add a panel for adding these features, with icon/text then allow to select from those at the room/bookable-unit level", I'm open to suggestions)
  - Room ID (For booking later)
  - Files for "Room gallery"

5) Default blueprint: For creating the rest of the pages
  - Hero image (to be used in the header)
  - Blocks (with the possibility to use the newly created blocks as explained in Home)

----

- You can see the mockups in ./docs/v2-build-notes/mockups/*.jpg for refference

