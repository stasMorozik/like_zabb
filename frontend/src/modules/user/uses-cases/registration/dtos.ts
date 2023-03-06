import { Dtos as CommonDtos } from '../../../common/dtos';

export namespace Dtos {
  export type Data = CommonDtos.Email & CommonDtos.Name & CommonDtos.Password & ConfirmPassword

  export type ConfirmPassword = {
    confirmPassword: string
  }
}
