import type {Config} from '@jest/types';

const config: Config.InitialOptions = {
  verbose: true,
  transform: {
  '^.+\\.ts?$': 'ts-jest',
  },
  modulePathIgnorePatterns: ["fake-adapter"]
}
export default config;
