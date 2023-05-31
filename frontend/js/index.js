(function () {
    let imageFound = true;

    async function sendPostRequest(endpoint, body = '') {
        return await fetch(endpoint, {
            method: 'POST',
            headers: {'Content-type': 'application/x-www-form-urlencoded'},
            body: body
        });
    }

    const getViewCount = async imageID => {
        const getViewCountResponse = await sendPostRequest('/backend/ajax/get-view-count.php', `image_id=${imageID}`);

        if (!getViewCountResponse.ok) {
            throw new Error('Failed to get the current view count');
        }
        document.getElementById('view-count').innerHTML = await getViewCountResponse.text();
    };

    const increaseViewCount = async imageID => {
        const increaseCountResponse = await sendPostRequest('/backend/ajax/increase-view-count.php', `image_id=${imageID}`);

        if (!increaseCountResponse.ok) {
            throw new Error('Failed to increase view count');
        }
    };

    async function loadImage() {
        const imageIDResponse = await sendPostRequest('/backend/ajax/get-image-id.php');
        if (!imageIDResponse.ok) {
            throw new Error('Failed to get the image identifier');
        }
        const imageID = await imageIDResponse.text();

        const imagePath = `/frontend/src/${imageID}.jpg`;

        const imgElement = document.getElementById('image'); // Assuming you have an <img> element with id 'myImage'

        imgElement.src = imagePath;

        imgElement.onload = async () => {
            if (imageFound) {
                await increaseViewCount(imageID);
                await getViewCount(imageID);

                setInterval(async function () {
                    await getViewCount(imageID);
                }, 5000);
            }
        };

        imgElement.onerror = () => {
            imageFound = false;
            imgElement.src = '/frontend/src/empty.jpg';
            throw new Error('Image not found');
        };
    }

    loadImage().catch(e => console.error('Error occurred while loading the image:', e))
})();

