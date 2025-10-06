import * as THREE from "https://unpkg.com/three@0.169.0/build/three.module.js?module";
import { OrbitControls } from "https://unpkg.com/three@0.169.0/examples/jsm/controls/OrbitControls.js?module";
import { GLTFLoader } from "https://unpkg.com/three@0.169.0/examples/jsm/loaders/GLTFLoader.js?module";
import { OBJLoader } from "https://unpkg.com/three@0.169.0/examples/jsm/loaders/OBJLoader.js?module";
import { Box3, Vector3 } from "https://unpkg.com/three@0.169.0/build/three.module.js?module";

// === Scéna, kamera, renderer ===
const scene = new THREE.Scene();
scene.background = new THREE.Color(0xeeeeee);

const camera = new THREE.PerspectiveCamera(75, window.innerWidth/window.innerHeight, 0.1, 1000);
camera.position.set(0, 1, 3);

const renderer = new THREE.WebGLRenderer({antialias: true});
renderer.setSize(window.innerWidth, window.innerHeight);
renderer.domElement.id = "Canvas";

// Najdeme sekci, kam chceme canvas vložit
const container = document.getElementById("canvasContainer");
container.appendChild(renderer.domElement);





// === OrbitControls ===
const controls = new OrbitControls(camera, renderer.domElement);
controls.enableDamping = true;

// === Světla ===
scene.add(new THREE.HemisphereLight(0xffffff, 0x444444, 1));
const dirLight = new THREE.DirectionalLight(0xffffff, 0.8);
dirLight.position.set(5, 10, 7.5);
scene.add(dirLight);

// === Funkce pro centrování a přiblížení ===
function centerAndZoom(object, camera, controls) {
    const box = new Box3().setFromObject(object);
    const size = new Vector3();
    const center = new Vector3();
    box.getSize(size);
    box.getCenter(center);

    // Posun objektu do středu scény
    object.position.sub(center);

    // Spočítáme vhodnou vzdálenost kamery
    const maxDim = Math.max(size.x, size.y, size.z);
    const fov = camera.fov * (Math.PI / 180);
    let cameraZ = Math.abs(maxDim / 2 / Math.tan(fov / 2));

    camera.position.set(0, maxDim / 2, cameraZ * 1.5);
    camera.lookAt(0, 0, 0);

    if (controls) {
        controls.target.set(0, 0, 0);
        controls.update();
    }
}

// === Načítání souboru ===
function loadFile(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const contents = e.target.result;

        if (file.name.endsWith(".glb") || file.name.endsWith(".gltf")) {
        const loader = new GLTFLoader();
        loader.parse(contents, "", (gltf) => {
            const object = gltf.scene;
            scene.add(object);
            centerAndZoom(object, camera, controls);
        });
        }

        if (file.name.endsWith(".obj")) {
        const loader = new OBJLoader();
        const object = loader.parse(contents);
        scene.add(object);
        centerAndZoom(object, camera, controls);
        }
    };

    if (file.name.endsWith(".glb")) reader.readAsArrayBuffer(file);
    else reader.readAsText(file);
}

document.getElementById("fileInput").addEventListener("change", (event) => {
    const file = event.target.files[0];
    if (file) loadFile(file);
});

// === Animace ===
function animate() {
    requestAnimationFrame(animate);
    controls.update();
    renderer.render(scene, camera);
}
animate();

// === Přizpůsobení oknu ===
/*window.addEventListener("resize", () => {
  camera.aspect = window.innerWidth / window.innerHeight;
  camera.updateProjectionMatrix();
  renderer.setSize(window.innerWidth, window.innerHeight);
});*/

renderer.domElement.style.width = "50vw";
renderer.domElement.style.height = "30vw";