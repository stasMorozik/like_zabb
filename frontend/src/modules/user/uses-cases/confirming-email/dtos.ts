import { Dtos as CommonDtos } from '../../../common/dtos';

export namespace Dtos {
  export type Data = CommonDtos.Email & Code

  export type Code = {
    code: number
  }
}
